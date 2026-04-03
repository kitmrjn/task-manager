<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeLog extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'log_date', 'time_in', 'time_out', 'eod_notes'];

    protected $casts = [
        'log_date' => 'date',
        'time_in'  => 'datetime',
        'time_out' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ── Dynamic Computed Properties ──

    public function getTotalHoursAttribute()
    {
        if ($this->time_in && $this->time_out) {
            // Calculates difference in hours, rounded to 2 decimal places
            return round($this->time_in->floatDiffInHours($this->time_out), 2);
        }
        return 0;
    }

    public function getStatusAttribute()
    {
        if (!$this->time_out) return 'Incomplete';
        if ($this->total_hours < 8) return 'Partial';
        return 'Complete';
    }
}