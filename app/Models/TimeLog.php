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

    public function breaks()
    {
        return $this->hasMany(BreakLog::class);
    }

    // ── Dynamic Computed Properties ──

    public function getTotalBreakMinutesAttribute()
    {
        return $this->breaks()->sum('duration_minutes');
    }

    public function getTotalHoursAttribute()
    {
        if ($this->time_in && $this->time_out) {
            $grossMinutes = $this->time_in->diffInMinutes($this->time_out);
            $netMinutes = max(0, $grossMinutes - $this->total_break_minutes);
            return round($netMinutes / 60, 2);
        }
        return 0;
    }

    public function getStatusAttribute()
    {
        if (!$this->time_out) return 'Active';
        if ($this->total_hours < 8) return 'Partial';
        return 'Complete';
    }
}