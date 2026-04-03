<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'log_date',
        'time_in',
        'time_out',
        'eod_notes',
    ];

    protected $casts = [
        'log_date' => 'date',
        'time_in'  => 'datetime',
        'time_out' => 'datetime',
    ];

    /**
     * Get the user that owns the time log.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}