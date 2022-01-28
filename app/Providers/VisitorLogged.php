<?php

namespace App\Providers;

use App\Models\Staff;
use App\Models\Visitor;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VisitorLogged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    public $visitorId;
    public $staffId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($visitorId, $staffId)
    {
        $this->visitorId = $visitorId;
        $this->staffId = $staffId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
