<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Webklex\IMAP\Facades\Client;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class EmailController extends Controller
{
    /**
     * Store the user's custom A2 Hosting Email configuration.
     */
    public function storeSettings(Request $request)
    {
        $request->validate([
            'email_imap_host' => 'required|string|max:255',
            'email_imap_port' => 'required|integer',
            'email_smtp_host' => 'required|string|max:255',
            'email_smtp_port' => 'required|integer',
            'email_username'  => 'required|email|max:255',
            'email_password'  => 'required|string',
        ]);

        $user = Auth::user();
        
        $user->update([
            'email_imap_host' => $request->email_imap_host,
            'email_imap_port' => $request->email_imap_port,
            'email_smtp_host' => $request->email_smtp_host,
            'email_smtp_port' => $request->email_smtp_port,
            'email_username'  => $request->email_username,
            // Encrypt the password securely before saving to the database
            'email_password'  => Crypt::encryptString($request->email_password),
        ]);

        return redirect()->route('email.index')->with('success', 'Email account connected successfully!');
    }

    /**
     * Dynamically construct the IMAP Client based on the authenticated user's settings.
     */
    private function getImapClient()
    {
        $user = Auth::user();

        if (!$user->email_username || !$user->email_password) {
            throw new \Exception('Missing credentials.');
        }

        // Decrypt the password on the fly
        $decryptedPassword = Crypt::decryptString($user->email_password);

        $client = Client::make([
            'host'          => $user->email_imap_host,
            'port'          => $user->email_imap_port,
            'encryption'    => 'ssl', // A2 Hosting default
            'validate_cert' => true,
            'username'      => $user->email_username,
            'password'      => $decryptedPassword,
            'protocol'      => 'imap',
        ]);

        $client->connect();
        return $client;
    }

    private function getRealFolderName($client, $requestedFolder)
    {
        if (strtoupper($requestedFolder) === 'INBOX') return $client->getFolder('INBOX');
        $possiblePaths = [$requestedFolder, 'INBOX.' . $requestedFolder, $requestedFolder . 's', 'INBOX.' . $requestedFolder . 's'];
        foreach ($possiblePaths as $path) {
            try { return $client->getFolder($path); } catch (\Exception $e) { continue; }
        }
        return $client->getFolder('INBOX');
    }

    private function getExactFolderPath($client, $targetName)
    {
        try {
            $folders = $client->getFolders(false);
            foreach ($folders as $folder) {
                if (stripos($folder->path, $targetName) !== false) return $folder->path;
            }
        } catch (\Exception $e) {}
        return 'INBOX.' . $targetName;
    }

    public function index(Request $request)
    {
        // Safety Net: Increase memory and execution time for heavy IMAP operations
        ini_set('memory_limit', '256M');
        set_time_limit(120); // Allow up to 2 minutes for the IMAP server to respond

        $user = Auth::user();

        // CONTEXTUAL ONBOARDING INTERCEPT:
        // If the user hasn't set up their credentials, show the setup screen.
        if (!$user->email_username || !$user->email_password || !$user->email_imap_host) {
            return view('email.setup');
        }

        try {
            $client = $this->getImapClient();
        } catch (\Exception $e) {
            // If connection fails (e.g. wrong password), clear the bad settings and prompt again
            $user->update(['email_password' => null]);
            return view('email.setup')->with('error', 'Connection failed. Please check your credentials and try again.');
        }
        
        $currentFolder = $request->query('folder', 'INBOX');
        $folder = $this->getRealFolderName($client, $currentFolder);
        $currentFolder = $folder->name;

        // 1. Grab Search and Pagination variables
        $filter = $request->query('filter', 'all');
        $search = $request->query('search', '');
        $page = max(1, (int)$request->query('page', 1));

        $query = $folder->query();

        if (!empty($search)) $query->whereText($search);
        
        if ($filter === 'unread') $query->unseen();
        else $query->all();

        // 4. Paginate the query. 
        $rawMessages = $query->setFetchBody(false)->limit(30, $page)->get();

        // Explicitly sort the collection descending by date so the absolute newest is always at index 0
        $messages = $rawMessages->filter(function($msg) {
            return !$msg->hasFlag('Deleted') && !$msg->hasFlag('\Deleted') && !$msg->hasFlag('DELETED');
        })
        ->sortByDesc(function($msg) {
            return $msg->getDate();
        })
        ->take(15);

        $selectedMessage = null;
        $emailBody = '';
        
        if ($request->has('uid')) {
            $selectedMessage = $folder->query()->getMessageByUid($request->uid);
            
            if ($selectedMessage) {
                if (str_contains(strtoupper($currentFolder), 'INBOX')) {
                    $selectedMessage->setFlag('Seen'); 
                }

                $emailBody = $selectedMessage->hasHTMLBody() ? $selectedMessage->getHTMLBody() : nl2br(e((string) $selectedMessage->getTextBody()));

                if ($selectedMessage->hasAttachments()) {
                    foreach ($selectedMessage->getAttachments() as $attachment) {
                        $cid = $attachment->id ? trim($attachment->id, '<>') : null;
                        if (!$cid && $attachment->content_id) {
                            $cid = trim($attachment->content_id, '<>');
                        }
                        if ($cid) {
                            $base64 = base64_encode($attachment->content);
                            $mime = $attachment->mime ?? 'image/png';
                            $dataUri = "data:{$mime};base64,{$base64}";
                            $emailBody = str_replace("cid:$cid", $dataUri, $emailBody);
                        }
                    }
                }
            }
        }

        try { $inboxUnreadCount = $client->getFolder('INBOX')->query()->unseen()->count(); } 
        catch (\Exception $e) { $inboxUnreadCount = 0; }

        return view('email.index', compact('messages', 'selectedMessage', 'filter', 'currentFolder', 'inboxUnreadCount', 'emailBody', 'search', 'page'));
    }

    public function compose()
    {
        return view('email.compose');
    }

    public function send(Request $request)
    {
        $request->validate([
            'to' => 'required|email',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'attachments.*' => 'nullable|file|max:10240', 
        ]);

        $user = Auth::user();

        if (!$user->email_username || !$user->email_password) {
            return redirect()->route('email.index')->with('error', 'Your email settings are not configured.');
        }

        $decryptedPassword = Crypt::decryptString($user->email_password);

        // Dynamically build the SMTP mailer for the current user
        $mailer = Mail::build([
            'transport'  => 'smtp',
            'host'       => $user->email_smtp_host,
            'port'       => $user->email_smtp_port,
            'encryption' => 'ssl',
            'username'   => $user->email_username,
            'password'   => $decryptedPassword,
            'timeout'    => null,
        ]);

        $mailer->html($request->body, function ($message) use ($request, $user) {
            $message->from($user->email_username, $user->name)
                    ->to($request->to)
                    ->subject($request->subject);

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $message->attach($file->getRealPath(), [
                        'as' => $file->getClientOriginalName(),
                        'mime' => $file->getClientMimeType(),
                    ]);
                }
            }
        });

        return redirect()->route('email.index', ['folder' => 'Sent'])->with('success', 'Email sent successfully!');
    }

    public function destroy(Request $request, $uid)
    {
        try {
            $client = $this->getImapClient();
        } catch (\Exception $e) {
            return redirect()->route('email.index')->with('error', $e->getMessage());
        }
        
        $currentFolder = $request->input('current_folder', 'INBOX');
        $folder = $this->getRealFolderName($client, $currentFolder);
        $message = $folder->query()->getMessageByUid($uid);
        
        if ($message) {
            try {
                $trashPath = $this->getExactFolderPath($client, 'Trash');
                if ($message->copy($trashPath)) {
                    $message->setFlag('Deleted');
                } else { throw new \Exception("Server refused to copy the message."); }
            } catch (\Exception $e) {
                return redirect()->route('email.index', ['folder' => $currentFolder])->with('error', "Could not delete: " . $e->getMessage());
            }
        }
        return redirect()->route('email.index', ['folder' => $currentFolder])->with('success', 'Email moved to Trash.');
    }

    public function archive(Request $request, $uid)
    {
        try {
            $client = $this->getImapClient();
        } catch (\Exception $e) {
            return redirect()->route('email.index')->with('error', $e->getMessage());
        }
        
        $currentFolder = $request->input('current_folder', 'INBOX');
        $folder = $this->getRealFolderName($client, $currentFolder);
        $message = $folder->query()->getMessageByUid($uid);
        
        if ($message) {
            try {
                $archivePath = $this->getExactFolderPath($client, 'Archive');
                if ($message->copy($archivePath)) {
                    $message->setFlag('Deleted');
                } else { throw new \Exception("Server refused to copy the message."); }
            } catch (\Exception $e) {
                return redirect()->route('email.index', ['folder' => $currentFolder])->with('error', "Could not archive: " . $e->getMessage());
            }
        }
        return redirect()->route('email.index', ['folder' => $currentFolder])->with('success', 'Email Archived.');
    }

    public function unreadCount()
    {
        try {
            $client = $this->getImapClient();
            $count = $client->getFolder('INBOX')->query()->unseen()->count();
            return response()->json(['count' => $count]);
        } catch (\Exception $e) { 
            return response()->json(['count' => 0]); 
        }
    }

    public function show($uid) { 
        return redirect()->route('email.index', ['uid' => $uid]); 
    }

    public function downloadAttachment($folder, $uid, $filename)
    {
        try {
            $client = $this->getImapClient();
        } catch (\Exception $e) {
            abort(403, 'Email settings not configured.');
        }
        
        $imapFolder = $this->getRealFolderName($client, $folder);
        $message = $imapFolder->query()->getMessageByUid($uid);
        
        if ($message) {
            $attachments = $message->getAttachments();
            foreach ($attachments as $attachment) {
                if ($attachment->name === base64_decode($filename)) {
                    return response($attachment->content)
                        ->header('Content-Type', $attachment->mime)
                        ->header('Content-Disposition', 'attachment; filename="' . $attachment->name . '"');
                }
            }
        }
        abort(404, 'Attachment not found.');
    }
}