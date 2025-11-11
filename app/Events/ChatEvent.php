<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatEvent  implements ShouldBroadcastNow
{
    use SerializesModels;

    public $message;
    public $user;
    public $imageUrls;
    public $createdAt;
    public $fullName;
    public $senderImage;
    public $messageId;

    public $userType;

    /**
     * Create a new event instance.
     */
    public function __construct($message, $user , $imageUrls , $createdAt, $fullName , $senderImage , $messageId , $userType)
    {
        $this->message = $message;
        $this->user = $user;
        $this->imageUrls = $imageUrls;
        $this->createdAt = $createdAt;
        $this->fullName = $fullName;
        $this->senderImage = $senderImage;
        $this->messageId = $messageId;
        $this->userType = $userType;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('chat-message'),
        ];
    }

    public function broadcastWith()
    {
        return [
            'message' => $this->message,
            'user' => $this->user,
            'imageUrls' => $this->imageUrls,
            'createdAt' => $this->createdAt,
            'fullName' => $this->fullName,
            'senderImage' => $this->senderImage,
            'messageId' => $this->messageId,
            'userType' => $this->userType
        ];
    }

    public function broadcastAs()
    {
        return 'ChatEvent';
    }
}
