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
        'calendar_type',
        'recurrence',
        'recurrence_until',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}