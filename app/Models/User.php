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
        'campaign_id',
        'team_leader_id',
        'last_active',
        'is_active',
        'email_imap_host',
        'email_imap_port',
        'email_smtp_host',
        'email_smtp_port',
        'email_username',
        'email_password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'email_password',
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


    // ── Role Hierarchy & Helpers ─────────────────────────────────────

    /**
     * Assigns a numerical weight to roles for easy hierarchical checking.
     */
    public function roleLevel(): int
    {
        return match ($this->role) {
            'super_admin'            => 4,
            'admin'                  => 3,
            'manager', 'team_leader' => 2,
            default                  => 1, // team_member or default
        };
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAtLeastAdmin(): bool
    {
        return $this->roleLevel() >= 3;
    }

    public function isAtLeastManager(): bool
    {
        return $this->roleLevel() >= 2;
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
        // Automatically grant ALL permissions to Super Admin
        if ($this->isSuperAdmin()) return true; 
        
        return (bool) ($this->getPermissions()->{$permission} ?? true);
    }
}