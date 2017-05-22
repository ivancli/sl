<?php

namespace App\Jobs;

use App\Models\Alert as AlertModel;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class Alert implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $alert;
    protected $lastActiveAt = null;
    protected $user;

    protected $emailData;

    /**
     * Create a new job instance.
     *
     * @param AlertModel $alert
     */
    public function __construct(AlertModel $alert)
    {
        $this->alert = $alert;
        $this->user = $alert->user;

        if (!is_null($this->alert->last_active_at)) {
            $this->lastActiveAt = Carbon::createFromFormat('Y-m-d H:i:s', $this->alert->last_active_at);
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /*TODO compare last active at and last price change date*/
        switch ($this->alert->alert_type) {
            case 'base':
                $this->processBasicAlert();
                break;
            case 'advanced':
                $this->processAdvancedAlert();
                break;

        }

        /*TODO if it's within crawling frequency, ignore*/

        /*TODO if it's outside crawling frequency, trigger alert email*/

        $this->alert->setLastActiveAt();
    }

    protected function processBasicAlert()
    {


        switch ($this->alert->comp_type) {
            case 'my_price':

                break;
            case 'price_chance':
                $sites = $this->user->sites;

                $alertSites = collect();

                foreach ($sites as $site) {
                    $item = $site->item;
                    if (!is_null($item)) {
                        if (!is_null($item->lastChangedAt)) {
                            if (!is_null($this->lastActiveAt)) {
                                $lastChangedAt = Carbon::createFromFormat('Y-m-d H:i:s', $item->lastChangedAt);
                                if ($lastChangedAt > $this->lastActiveAt) {
                                    $alertSites->push($site);
                                }
                            } else {
                                $alertSites->push($site);
                            }
                        }
                    }
                }

                /*now $sites has all sites which have price changes*/


                break;
        }
    }

    protected function processAdvancedAlert()
    {

    }

}
