<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model
{
    protected $fillable = [
        'user_id',
        'can_view_calendar',
        'can_view_analytics',
        'can_view_team',
        'can_view_reports',
        'can_create_tasks',
        'can_delete_tasks',
        'can_edit_tasks',    // ← add
        'can_add_column',    // ← add
    ];

    protected $casts = [
        'can_view_calendar'  => 'boolean',
        'can_view_analytics' => 'boolean',
        'can_view_team'      => 'boolean',
        'can_view_reports'   => 'boolean',
        'can_create_tasks'   => 'boolean',
        'can_delete_tasks'   => 'boolean',
        'can_edit_tasks'     => 'boolean',  // ← add
        'can_add_column'     => 'boolean',  // ← add
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}