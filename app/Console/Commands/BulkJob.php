<?php

namespace App\Console\Commands;

use App\Contracts\Repositories\Product\BulkJobContract;
use App\Jobs\BulkJob\Product;
use App\Jobs\BulkJob\Url;
use Illuminate\Console\Command;

class BulkJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bulk-job';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command splits every import job into chunks and push to queue';


    protected $bulkJobRepo;

    /**
     * Create a new command instance.
     *
     * @param BulkJobContract $bulkJobContract
     */
    public function __construct(BulkJobContract $bulkJobContract)
    {
        $this->bulkJobRepo = $bulkJobContract;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $bulkJobs = $this->bulkJobRepo->all([
            'status' => 'waiting',
            'archived' => 'n'
        ]);

        $bulkJobs->each(function ($bulkJob) {
            if ($bulkJob->completed >= $bulkJob->chunks) {
                $bulkJob->statusNull();
                $bulkJob->archive();
                return true;
            }

            $type = $bulkJob->type;
            $chunks = $bulkJob->chunks;
            $bulkJob->statusProcessing();
            switch ($type) {
                case 'product':
                    for ($i = 1; $i <= $chunks; $i++) {
                        dispatch((new Product($bulkJob, $i))->onQueue('bulk-job'));
                    }
                    break;
                case 'url':
                    for ($i = 1; $i <= $chunks; $i++) {
                        dispatch((new Url($bulkJob, $i))->onQueue('bulk-job'));
                    }
                    break;
            }
        });
    }
}
