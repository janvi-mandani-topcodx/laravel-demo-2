<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    public $fillable = [
        'chat_id',
        'user_type',
        'message',
        'attachment_name',
        'attachment_url',
    ];
    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }
}
