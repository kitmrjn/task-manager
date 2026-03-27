<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 
        'date', 
        'time', 
        'type', 
        'color', 
        'description', 
        'user_id',
        'recurrence',       // Add this line
        'recurrence_until'  // Add this line
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}