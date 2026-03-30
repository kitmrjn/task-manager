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
        'last_active',
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
        ];
    }

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

    // ← NEW
    public function permissions()
    {
        return $this->hasOne(UserPermission::class);
    }

    /**
     * Get permissions, creating defaults if they don't exist yet.
     */
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

    /**
     * Check a single permission. Admins always return true.
     */
    public function can_access(string $permission): bool
    {
        if ($this->role === 'admin') return true;
        return (bool) ($this->getPermissions()->{$permission} ?? true);
    }
}