<?php

namespace App\Events\UserManagement\Permission;

use App\Models\Permission;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AfterShow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $permission;

    /**
     * Create a new event instance.
     *
     * @param Permission $permission
     */
    public function __construct(Permission $permission)
    {
        $this->permission = $permission;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
