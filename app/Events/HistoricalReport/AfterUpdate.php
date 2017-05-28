<?php

namespace App\Events\HistoricalReport;

use App\Models\HistoricalReport;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AfterUpdate
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $historicalReport;

    /**
     * Create a new event instance.
     *
     * @param HistoricalReport $historicalReport
     */
    public function __construct(HistoricalReport $historicalReport)
    {
        $this->historicalReport = $historicalReport;
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
