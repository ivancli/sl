<?php

namespace App\Mail\Alert;

use App\Models\Category;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AdvancedCategoryMyPrice extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public $category;

    public $products;

    public $subject = "SpotLite Price Alert";

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param Category $category
     * @param $products
     */
    public function __construct(User $user, Category $category, $products)
    {
        $this->user = $user;

        $this->category = $category;

        $this->products = $products;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.alerts.advanced_category_my_price');
    }
}
