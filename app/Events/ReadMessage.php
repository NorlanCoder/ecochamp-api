<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReadMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The message data to broadcast.
     *
     * @var mixed
     */
    public $message;

    /**
     * Create a new event instance.
     */
     public function __construct($message)
    {
        $this->message = $message;
    }

     /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     * 
     */
    public function broadcastOn(): Channel
    {

        return new Channel('messages.'. $this->message['id']);
    }

    /**
     * Get the broadcastable data.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'message' => $this->message,
        ];
    }
}
