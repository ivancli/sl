<?php

namespace App\Mail\Alert;

use App\Models\Product;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AdvancedProductPriceChange extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public $product;

    public $sites;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param Product $product
     * @param $sites
     */
    public function __construct(User $user, Product $product, $sites)
    {
        $this->user = $user;

        $this->product = $product;

        $this->sites = $sites;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.alerts.advanced_product_price_change');
    }
}
