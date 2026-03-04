<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['title', 'description', 'status', 'creator_id', 'assigned_to', 'due_date'];

    // Get the user who is assigned to this task
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // Get the user who created this task
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
}