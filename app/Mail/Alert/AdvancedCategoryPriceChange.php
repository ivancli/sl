<?php

namespace App\Mail\Alert;

use App\Models\Category;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AdvancedCategoryPriceChange extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public $category;

    public $sites;

    public $subject = "SpotLite Price Alert";

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param Category $category
     * @param $sites
     */
    public function __construct(User $user, Category $category, $sites)
    {
        $this->user = $user;

        $this->category = $category;

        $this->sites = $sites;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.alerts.advanced_category_price_change');
    }
}
