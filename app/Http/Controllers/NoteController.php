<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\NoteAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class NoteController extends Controller
{
public function index()
{
    $userId = Auth::id();

    $pinned   = Note::with('attachments')->where('user_id', $userId)
                    ->where('is_pinned', true)->where('is_archived', false)
                    ->latest()->get();

    $others   = Note::with('attachments')->where('user_id', $userId)
                    ->where('is_pinned', false)->where('is_archived', false)
                    ->latest()->get();

    $archived = Note::with('attachments')->where('user_id', $userId)
                    ->where('is_archived', true)
                    ->latest()->get();

    return view('notes.index', compact('pinned', 'others', 'archived'));
}
// Upload attachment
public function uploadAttachment(Request $request, Note $note)
{
    $this->authorizeNote($note);

    $request->validate([
        'file' => 'required|file|mimes:jpg,jpeg,png,gif|max:20480',
    ]);

    $file = $request->file('file');
    $path = $file->store('note-attachments/' . Auth::id(), 'public');

    $attachment = NoteAttachment::create([
        'note_id'   => $note->id,
        'user_id'   => Auth::id(),
        'filename'  => $file->getClientOriginalName(),
        'path'      => $path,
        'mime_type' => $file->getMimeType(),
        'size'      => $file->getSize(),
    ]);

    return response()->json([
        'success' => true,
        'attachment' => [
            'id'       => $attachment->id,
            'filename' => $attachment->filename,
            'url'      => $attachment->url,
            'size'     => $attachment->human_size,
        ],
    ]);
}

// Delete attachment
public function deleteAttachment(Note $note, NoteAttachment $attachment)
{
    $this->authorizeNote($note);

    if ($attachment->note_id !== $note->id) abort(403);

    Storage::disk('public')->delete($attachment->path);
    $attachment->delete();

    return response()->json(['success' => true]);
}

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'body'  => 'nullable|string',
        ]);

        Note::create([
            'user_id' => Auth::id(),
            'title'   => $request->title,
            'body'    => $request->body,
        ]);

        return response()->json(['success' => true]);
    }

    public function update(Request $request, Note $note)
    {
        $this->authorizeNote($note);

        $request->validate([
            'title' => 'nullable|string|max:255',
            'body'  => 'nullable|string',
        ]);

        $note->update([
            'title' => $request->title,
            'body'  => $request->body,
        ]);

        return response()->json(['success' => true]);
    }

    public function togglePin(Note $note)
    {
        $this->authorizeNote($note);
        $note->update(['is_pinned' => !$note->is_pinned]);
        return response()->json(['success' => true, 'is_pinned' => $note->is_pinned]);
    }

    public function toggleArchive(Note $note)
    {
        $this->authorizeNote($note);
        $note->update([
            'is_archived' => !$note->is_archived,
            'is_pinned'   => false, // unpin when archiving
        ]);
        return response()->json(['success' => true, 'is_archived' => $note->is_archived]);
    }

    public function destroy(Note $note)
    {
        $this->authorizeNote($note);
        $note->delete();
        return response()->json(['success' => true]);
    }

    private function authorizeNote(Note $note)
    {
        if ($note->user_id !== Auth::id()) abort(403);
    }
}