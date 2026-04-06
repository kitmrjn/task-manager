<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Webklex\IMAP\Facades\Client;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    /**
     * Display a listing of the emails (Inbox).
     */
    public function index()
    {
        // Connect to the IMAP server defined in config
        $client = Client::account('default');
        $client->connect();

        // Get the Inbox folder
        $folder = $client->getFolder('INBOX');

        // Fetch the latest 15 emails for performance
        $messages = $folder->messages()->all()->limit(15, 0)->get();

        return view('email.index', compact('messages'));
    }

    /**
     * Show the form for creating a new email.
     */
    public function compose()
    {
        return view('email.compose');
    }

    /**
     * Send the newly composed email via SMTP.
     */
    public function send(Request $request)
    {
        $request->validate([
            'to' => 'required|email',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        // Use Laravel's built in Mail facade (SMTP)
        Mail::html($request->body, function ($message) use ($request) {
            $message->to($request->to)
                    ->subject($request->subject);
        });

        return redirect()->route('email.index')->with('success', 'Email sent successfully!');
    }

    /**
     * Display the specified email.
     */
    public function show($uid)
    {
        $client = Client::account('default');
        $client->connect();
        
        $folder = $client->getFolder('INBOX');
        // Fetch the specific message by its UID
        $message = $folder->query()->getMessageByUid($uid);

        if (!$message) {
            abort(404, 'Email not found.');
        }

        // Mark as read
        $message->setFlag('Seen');

        return view('email.show', compact('message'));
    }
}