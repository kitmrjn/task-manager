<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    // 1. Allow these fields to be saved
    protected $fillable = ['name', 'description', 'user_id'];

    // 2. A Board belongs to the Admin who created it
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // 3. A Board has many Columns (To Do, In Progress, Done)
    public function columns()
    {
        return $this->hasMany(BoardColumn::class)->orderBy('order');
    }
}