<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class Discussion implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The message data to broadcast.
     *
     * @var mixed
     */
    public $chat;


    /**
     * Create a new event instance.
     */
    public function __construct($chat)
    {
        $this->chat = $chat;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn(): Channel
    {
       
        return new Channel('discussions.'. $this->chat['to_id']);
    }

    /**
     * Get the broadcastable data.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'message' => $this->chat,
        ];
    }
}
