<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarEvent extends Model
{
    use HasFactory;

    // This tells Laravel it's okay to save these specific fields
    protected $fillable = [
        'title', 
        'date', 
        'time', 
        'type', 
        'color', 
        'description', 
        'user_id'
    ];

    // Optional: Add the relationship to the User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}