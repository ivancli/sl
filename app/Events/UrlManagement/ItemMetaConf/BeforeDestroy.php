<?php

namespace App\Events\UrlManagement\ItemMetaConf;

use App\Models\ItemMetaConf;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BeforeDestroy
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $itemMetaConf;
    /**
     * Create a new event instance.
     *
     * @param ItemMetaConf $itemMetaConf
     */
    public function __construct(ItemMetaConf $itemMetaConf)
    {
        $this->itemMetaConf = $itemMetaConf;
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
