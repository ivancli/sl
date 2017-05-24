<?php

namespace App\Mail\Alert;

use App\Models\Alert;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AdvancedProductCustom extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public $alert;

    public $product;

    public $sites;

    public $subject = "SpotLite Price Alert";

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param Alert $alert
     * @param $sites
     */
    public function __construct(User $user, Alert $alert, $sites)
    {
        $this->user = $user;

        $this->alert = $alert;

        $this->product = $alert->alertable;

        $this->sites = $sites;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.alerts.advanced_product_custom');
    }
}
