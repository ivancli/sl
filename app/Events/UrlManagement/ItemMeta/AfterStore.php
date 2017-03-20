<?php

namespace App\Events\UrlManagement\ItemMeta;

use App\Models\ItemMeta;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AfterStore
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $itemMeta;

    /**
     * Create a new event instance.
     *
     * @param ItemMeta $itemMeta
     */
    public function __construct(ItemMeta $itemMeta)
    {
        $this->itemMeta = $itemMeta;
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
