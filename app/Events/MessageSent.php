<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public object $autor,
        public object $message
    ) {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel
     */
    public function broadcastOn(): Channel
    {
        return new Channel('chat');
    }

    public function broadcastWith(): array
    {
        return [
            "user_id" => $this->autor->id,
            "user_name" => $this->autor->name,
            "message" => $this->message->mensaje,
            "files" => $this->message->archivo_mensaje,
            "date" => $this->message->created_at->format('dmY'),
            "time" => $this->message->created_at->format('h:i a')
        ];
    }
}
