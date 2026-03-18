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
        'priority', 
        'due_date', 
        'start_date',
        'order',
        'is_completed', // <--- ADD THIS LINE HERE
        
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
    //Checklist Item
    public function checklistItems() {
        return $this->hasMany(ChecklistItem::class);
    }
    //Adding member to task
    public function members()
    {
    // This tells Laravel a task can have many users through the pivot table
        return $this->belongsToMany(User::class, 'task_user');
    }
    //Activity Logs
    public function activities()
    {
        return $this->hasMany(TaskActivity::class)->latest();
    }    
}