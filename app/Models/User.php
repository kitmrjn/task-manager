<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'campaign_id',     // ← NEW
        'team_leader_id',  // ← NEW
        'last_active',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'last_active'       => 'datetime', 
            'is_active'         => 'boolean',        
        ];
    }

    // ── Existing Relationships ───────────────────────────────────────

    public function tasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    public function createdTasks()
    {
        return $this->hasMany(Task::class, 'creator_id');
    }

    public function boards()
    {
        return $this->hasMany(Board::class, 'user_id');
    }

    public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    public function permissions()
    {
        return $this->hasOne(UserPermission::class);
    }

    // ── NEW Relationships ────────────────────────────────────────────

    /**
     * Get the user's assigned campaign.
     */
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * Get the user's assigned team leader.
     */
    public function teamLeader()
    {
        return $this->belongsTo(User::class, 'team_leader_id');
    }

    /**
     * Get the users that this user leads (if they are a team leader).
     */
    public function teamMembers()
    {
        return $this->hasMany(User::class, 'team_leader_id');
    }

    // ── Permission Helpers ───────────────────────────────────────────

    public function getPermissions(): UserPermission
    {
        return $this->permissions ?? $this->permissions()->create([
            'can_view_calendar'  => true,
            'can_view_analytics' => true,
            'can_view_team'      => true,
            'can_view_reports'   => true,
            'can_create_tasks'   => true,
            'can_delete_tasks'   => true,
        ]);
    }

    public function can_access(string $permission): bool
    {
        // Notice we changed 'admin' to 'super_admin' to match your new requirement
        if ($this->role === 'super_admin') return true; 
        
        return (bool) ($this->getPermissions()->{$permission} ?? true);
    }
}