<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserValidId extends Model
{
    protected $fillable = [
        'user_id',
        'id_type',
        'file_path',
        'original_filename',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}