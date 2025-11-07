<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    public $fillable = [
        'user_id',
    ];
    public function chatMessages()
    {
        return $this->hasMany(ChatMessage::class);
    }

    public  function user()
    {
        return $this->belongsTo(User::class);
    }
}
