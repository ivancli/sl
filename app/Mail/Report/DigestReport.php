<?php

namespace App\Mail\Report;

use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class DigestReport extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public $report;

    public $cheapestProductCount;

    public $mostExpensiveProductCount;

    public $crawlFailCount;

    public $priceChangeCount;

    public $products;

    /**
     * Create a new message instance.
     *
     * @param Report $report
     * @param $reportDetail
     */
    public function __construct(Report $report, $reportDetail)
    {
        $this->user = $report->user;
        $this->report = $report;
        $this->cheapestProductCount = $reportDetail->get('cheapest_product_count', 0);
        $this->mostExpensiveProductCount = $reportDetail->get('most_expensive_product_count', 0);
        $this->crawlFailCount = $reportDetail->get('crawl_fail_count', 0);
        $this->priceChangeCount = $reportDetail->get('price_change_count', 0);
        $this->products = $reportDetail->get('products', collect());

        switch ($report->frequency) {
            case 'day':
                $this->subject('SpotLite Daily Digest Report');
                break;
            case 'week':
                $this->subject('SpotLite Weekly Digest Report');
                break;
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.reports.digest.index');
    }
}
