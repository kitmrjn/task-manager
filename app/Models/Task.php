<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'board_column_id', 'title', 'description', 
        'assigned_to', 'priority', 'due_date', 'order'
    ];

    // 1. This Task belongs inside a specific Column
    public function column()
    {
        return $this->belongsTo(BoardColumn::class, 'board_column_id');
    }

    // 2. This Task is assigned to a specific User
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}