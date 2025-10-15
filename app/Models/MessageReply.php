<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageReply extends Model
{
    protected $fillable = [
        'message_id',
        'send_by_admin',
        'message',
    ];
    public function message()
    {
        return $this->belongsTo(Message::class);
    }
}
