<?php

namespace App\Console\Commands;

use App\Models\Alert as AlertModel;
use App\Jobs\Alert as AlertJob;

use App\Contracts\Repositories\Alert\AlertContract;
use Carbon\Carbon;
use Illuminate\Console\Command;

/**
 * This is a command for checking which alerts need to be triggered
 * Class Alert
 * @package App\Console\Commands
 */
class Alert extends Command
{
    protected $alertRepo;
    protected $carbon;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alert {alert_id?} {--active}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Loop through all alerts to trigger each of them';

    /**
     * Create a new command instance.
     *
     * @param AlertContract $alertContract
     * @param Carbon $carbon
     */
    public function __construct(AlertContract $alertContract, Carbon $carbon)
    {
        $this->alertRepo = $alertContract;
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
        $alert_id = $this->argument('alert_id');

        if (!is_null($alert_id)) {
            $alert = $this->alertRepo->get($alert_id);

            $this->processSingleAlert($alert);
        } else {
            $alerts = $this->alertRepo->all();

            $progressBar = $this->output->createProgressBar($alerts->count());

            $alerts->each(function ($alert, $index) use ($progressBar) {
                $progressBar->advance();
                $this->processSingleAlert($alert);
            });

            $progressBar->finish();
        }
    }

    protected function processSingleAlert(AlertModel $alert)
    {
        $this->pushToQueue($alert);
    }

    protected function pushToQueue(AlertModel $alert)
    {
        dispatch((new AlertJob($alert))->onQueue("alert"));
    }
}
