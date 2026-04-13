<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemoRead extends Model
{
    public $timestamps = false;

    protected $fillable = ['memo_id', 'user_id', 'read_at'];

    public function memo()
    {
        return $this->belongsTo(Memo::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}