<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'shift_start',
        'shift_end',
        'timezone',
        'operating_days',
    ];

    protected $casts = [
        'operating_days' => 'array',
    ];

    /**
     * Get the users associated with this campaign.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get a human-readable shift time range.
     */
    public function getShiftLabelAttribute(): string
    {
        if (!$this->shift_start || !$this->shift_end) return 'No schedule set';

        $fmt = fn($t) => date('g:i A', strtotime($t));
        return $fmt($this->shift_start) . ' – ' . $fmt($this->shift_end);
    }

    /**
     * Check whether the campaign has a schedule configured.
     */
    public function hasSchedule(): bool
    {
        return !is_null($this->shift_start) && !is_null($this->shift_end);
    }
}