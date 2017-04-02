<?php

namespace App\Events\Jobs\Crawl;

use App\Models\Url;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AfterFetchUrl
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $url;
    public $status;
    public $content;

    /**
     * Create a new event instance.
     *
     * @param Url $url
     * @param int $status
     * @param String $content
     */
    public function __construct(Url $url, String $content, Integer $status = null)
    {
        $this->url = $url;
        $this->content = $content;
        $this->status = $status;
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
