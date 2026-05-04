<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BreakLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'time_log_id',
        'break_type',
        'start_time',
        'end_time',
        'duration_minutes',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time'   => 'datetime',
    ];

    public function timeLog()
    {
        return $this->belongsTo(TimeLog::class);
    }
}