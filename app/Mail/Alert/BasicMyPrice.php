<?php

namespace App\Mail\Alert;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class BasicMyPrice extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public $products;

    public $subject = "SpotLite Price Alert";

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param $products
     */
    public function __construct(User $user, $products)
    {
        $this->user = $user;

        $this->products = $products;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.alerts.basic_my_price');
    }
}
