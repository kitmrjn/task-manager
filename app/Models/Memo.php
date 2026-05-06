<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Memo extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'created_by',
        'target_type',
        'target_id',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reads()
    {
        return $this->hasMany(MemoRead::class);
    }

    public function isReadBy(int $userId): bool
    {
        return $this->reads()->where('user_id', $userId)->exists();
    }

    /**
     * Check if this memo is visible to a given user.
     */
    public function isVisibleTo(User $user): bool
    {
        if ($this->target_type === 'all') return true;

        if ($this->target_type === 'campaign') {
            return $user->campaign_id && $user->campaign_id == $this->target_id;
        }

        if ($this->target_type === 'user') {
            return $user->id == $this->target_id;
        }

        return false;
    }
}