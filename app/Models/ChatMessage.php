<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ChatMessage extends Model implements HasMedia
{
    use InteractsWithMedia;
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

    public function getImageUrlAttribute()
    {
            $img = [];
        $chatImage = $this->getMedia('chat');
        if($chatImage){
            foreach ($chatImage as $image) {
                $img[] =  $image->getUrl();
            }
            return $img;
        }
        return null;
    }
}
