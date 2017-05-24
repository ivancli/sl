<?php

namespace App\Mail\Alert;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class BasicPriceChange extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public $sites;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param $sites
     */
    public function __construct(User $user, $sites)
    {
        $this->user = $user;

        $this->sites = $sites;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.alerts.basic_price_change');
    }
}
