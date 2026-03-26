<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'board_column_id',
        'title',
        'description',
        'assigned_to',
        'creator_id',      // ← NEW
        'priority',
        'due_date',
        'start_date',
        'order',
        'is_completed',
    ];

    public function column()
    {
        return $this->belongsTo(BoardColumn::class, 'board_column_id');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // ← NEW: who created this task
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function checklistItems()
    {
        return $this->hasMany(ChecklistItem::class);
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'task_user');
    }

    public function activities()
    {
        return $this->hasMany(TaskActivity::class)->latest();
    }

    public function attachments()
    {
        return $this->hasMany(TaskAttachment::class)->latest();
    }
}