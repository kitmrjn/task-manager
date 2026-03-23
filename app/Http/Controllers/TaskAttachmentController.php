<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TaskAttachmentController extends Controller
{
    /**
     * Upload one or more attachments to a task.
     * POST /tasks/{task}/attachments
     */
    public function store(Request $request, Task $task)
    {
        $request->validate([
            'files'   => 'required|array|max:10',
            'files.*' => 'file|max:10240', // 10 MB per file
        ]);

        $uploaded = [];

        foreach ($request->file('files') as $file) {
            $original  = $file->getClientOriginalName();
            $mime      = $file->getMimeType();
            $size      = $file->getSize();
            $filename  = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path      = $file->storeAs("task-attachments/{$task->id}", $filename, 'public');

            $attachment = $task->attachments()->create([
                'user_id'       => auth()->id(),
                'original_name' => $original,
                'filename'      => $filename,
                'mime_type'     => $mime,
                'size'          => $size,
                'path'          => $path,
            ]);

            $task->activities()->create([
                'user_id'     => auth()->id(),
                'action'      => 'attachment_added',
                'description' => "attached \"$original\"",
            ]);

            $uploaded[] = [
                'id'            => $attachment->id,
                'original_name' => $attachment->original_name,
                'mime_type'     => $attachment->mime_type,
                'size'          => $attachment->humanSize(),
                'url'           => $attachment->url(),
                'is_image'      => $attachment->isImage(),
            ];
        }

        return response()->json(['success' => true, 'attachments' => $uploaded]);
    }

    /**
     * Delete an attachment.
     * DELETE /attachments/{attachment}
     */
    public function destroy(TaskAttachment $attachment)
    {
        // Delete from disk
        Storage::disk('public')->delete($attachment->path);

        $attachment->task->activities()->create([
            'user_id'     => auth()->id(),
            'action'      => 'attachment_deleted',
            'description' => "removed attachment \"{$attachment->original_name}\"",
        ]);

        $attachment->delete();

        return response()->json(['success' => true]);
    }
}