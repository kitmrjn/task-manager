<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalendarEvent extends Model
{
    protected $fillable = [
        'user_id', 'title', 'description', 
        'date', 'time', 'type', 'color'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}