<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Webklex\IMAP\Facades\Client;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
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
        $client = Client::account('default');
        $client->connect();
        
        $currentFolder = $request->query('folder', 'INBOX');
        $folder = $this->getRealFolderName($client, $currentFolder);
        $currentFolder = $folder->name;

        // 1. Grab Search and Pagination variables from the URL
        $filter = $request->query('filter', 'all');
        $search = $request->query('search', '');
        $page = max(1, (int)$request->query('page', 1));

        $query = $folder->query();

        // 2. Apply Search if a keyword is entered
        if (!empty($search)) {
            $query->whereText($search);
        }

        // 3. Apply Filters
        if ($filter === 'unread') {
            $query->unseen();
        } else {
            $query->all();
        }

        // 4. Paginate the query (Fetch 30 to account for ghost messages)
        $rawMessages = $query->limit(30, $page)->get();

        $messages = $rawMessages->filter(function($msg) {
            return !$msg->hasFlag('Deleted') && !$msg->hasFlag('\Deleted') && !$msg->hasFlag('DELETED');
        })->take(15);

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

        // Pass $search and $page back to the view
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

        Mail::html($request->body, function ($message) use ($request) {
            $message->to($request->to)->subject($request->subject);
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
        $client = Client::account('default');
        $client->connect();
        
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
        $client = Client::account('default');
        $client->connect();
        
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
            $client = Client::account('default');
            $client->connect();
            $count = $client->getFolder('INBOX')->query()->unseen()->count();
            return response()->json(['count' => $count]);
        } catch (\Exception $e) { return response()->json(['count' => 0]); }
    }

    public function show($uid) { return redirect()->route('email.index', ['uid' => $uid]); }

    public function downloadAttachment($folder, $uid, $filename)
    {
        $client = Client::account('default');
        $client->connect();
        
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