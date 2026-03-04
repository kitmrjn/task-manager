<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoardColumn extends Model
{
    protected $fillable = ['board_id', 'title', 'order'];

    // 1. This Column belongs to a specific Board
    public function board()
    {
        return $this->belongsTo(Board::class);
    }

    // 2. This Column contains many Tasks
    public function tasks()
    {
        return $this->hasMany(Task::class)->orderBy('order');
    }
}