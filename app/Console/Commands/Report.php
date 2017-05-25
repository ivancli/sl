<?php

namespace App\Console\Commands;

use App\Contracts\Repositories\Report\ReportContract;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\Report as ReportModel;
use App\Jobs\Report as ReportJob;

class Report extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report {report_id?} {--active}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Loop through all reports to trigger each of them';

    protected $reportRepo;

    protected $carbon;

    /**
     * Create a new command instance.
     *
     * @param ReportContract $reportContract
     * @param Carbon $carbon
     */
    public function __construct(ReportContract $reportContract, Carbon $carbon)
    {
        $this->reportRepo = $reportContract;

        $this->carbon = $carbon;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $report_id = $this->argument('report_id');

        if (!is_null($report_id)) {
            $report = $this->reportRepo->get($report_id);

            $this->processSingleReport($report);
        } else {
            $reports = $this->reportRepo->all();

            $progressBar = $this->output->createProgressBar($reports->count());

            $reports->each(function ($alert, $index) use ($progressBar) {
                $progressBar->advance();
                $this->processSingleReport($alert);
            });

            $progressBar->finish();
        }
    }

    protected function processSingleReport(ReportModel $report)
    {
        $this->pushToQueue($report);
    }

    protected function pushToQueue(ReportModel $report)
    {
        dispatch((new ReportJob($report))->onQueue("report"));
    }
}
