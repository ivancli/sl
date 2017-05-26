<?php

namespace App\Mail\Report;

use App\Models\HistoricalReport;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProductCategoryReport extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public $report;

    public $category;

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
        $this->category = $report->reportable;
        $this->historicalReport = $historicalReport;
        $this->reportContent = base64_decode($historicalReport->content);
        $dateTime = Carbon::now()->format('YmdHis');
        $this->fileName = "{$this->historicalReport->file_name}_{$dateTime}.{$this->fileExtension}";

        switch ($report->frequency) {
            case 'day':
                $this->subject('SpotLite Daily Category Report');
                break;
            case 'week':
                $this->subject('SpotLite Weekly Category Report');
                break;
            case 'month':
                $this->subject('SpotLite Monthly Category Report');
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
        return $this->markdown('emails.reports.product.category')
            ->attachData($this->reportContent, "{$this->fileName}");
    }
}
