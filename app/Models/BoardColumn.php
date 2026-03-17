<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoardColumn extends Model
{
    // Add 'color' and 'description' to this array!
    protected $fillable = ['board_id', 'title', 'order', 'color', 'description'];

    public function board()
    {
        return $this->belongsTo(Board::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class)->orderBy('order');
    }
}