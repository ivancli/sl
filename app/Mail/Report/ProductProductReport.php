<?php

namespace App\Mail\Report;

use App\Models\HistoricalReport;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProductProductReport extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public $report;

    public $product;

    public $historicalReport;

    protected $reportContent;

    protected $fileName;

    protected $fileExtension = 'xlsx';

    /**
     * Create a new message instance.
     *
     * @param Report $report
     * @param HistoricalReport $historicalReport
     */
    public function __construct(Report $report, HistoricalReport $historicalReport)
    {
        $this->user = $report->user;
        $this->report = $report;
        $this->product = $report->reportable;
        $this->historicalReport = $historicalReport;
        $this->reportContent = base64_decode($historicalReport->content);
        $dateTime = Carbon::now()->format('YmdHis');
        $this->fileName = "{$this->historicalReport->file_name}_{$dateTime}.{$this->fileExtension}";

        switch ($report->frequency) {
            case 'day':
                $this->subject('SpotLite Daily Product Report');
                break;
            case 'week':
                $this->subject('SpotLite Weekly Product Report');
                break;
            case 'month':
                $this->subject('SpotLite Monthly Product Report');
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
        return $this->markdown('emails.reports.product.product')
            ->attachData($this->reportContent, "{$this->fileName}");
    }
}
