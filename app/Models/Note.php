<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Note extends Model
{
    use HasFactory;

protected $fillable = ['user_id', 'title', 'body', 'is_pinned', 'is_archived'];

protected $casts = [
    'is_pinned'   => 'boolean',
    'is_archived' => 'boolean',
];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function attachments()
{
    return $this->hasMany(NoteAttachment::class);
}
}