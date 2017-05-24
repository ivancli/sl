<?php

namespace App\Mail\Alert;

use App\Models\Product;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AdvancedProductMyPrice extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public $product;

    public $subject = "SpotLite Price Alert";

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param Product $product
     */
    public function __construct(User $user, Product $product)
    {
        $this->user = $user;

        $this->product = $product;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.alerts.advanced_product_my_price');
    }
}
