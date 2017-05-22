<?php

namespace App\Console;

use App\Contracts\Repositories\Admin\AppPrefContract;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\Crawl::class,
        Commands\Alert::class
    ];

    protected $appPrefRepo;

    public function __construct(Application $app, Dispatcher $events)
    {
        parent::__construct($app, $events);
    }

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        #region repositories binding
        $this->appPrefRepo = $this->app->make(AppPrefContract::class);
        #endregion

        $this->scheduleCrawlers($schedule);
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }

    protected function scheduleCrawlers(Schedule $schedule)
    {
        #region Crawl.php

        $schedule->command('crawl --active')
            ->withoutOverlapping()
            ->hourly()
            ->when(function () {
                #region validate reservation
                $crawlReservedAppPref = $this->appPrefRepo->get('CRAWL_RESERVED');

                #region check if task is reserved
                if (is_null($crawlReservedAppPref) || $crawlReservedAppPref->value == 'n') {

                    #region check if last reservation is within an hour
                    $crawlLastReservedAt = $this->appPrefRepo->get('CRAWL_LAST_RESERVED_AT');
                    if (!is_null($crawlLastReservedAt)) {
                        $lastReservedDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $crawlLastReservedAt->value);
                        $currentDateTime = Carbon::now();
                        if ($lastReservedDateTime->diffInHours($currentDateTime) > 0) {
                            return true;
                        }
                    } else {
                        //no reservation history
                        return true;
                    }
                    #endregion

                }
                #endregion

                return false;
            })
            ->before(function () {
                #region reserve crawler
                $this->appPrefRepo->store([
                    'element' => 'CRAWL_RESERVED',
                    'value' => 'y'
                ]);
                $this->appPrefRepo->store([
                    'element' => 'CRAWL_LAST_RESERVED_AT',
                    'value' => Carbon::now()->toDateTimeString()
                ]);
                #endregion
            })
            ->after(function () {
                #region release crawlers
                $this->appPrefRepo->store([
                    'element' => 'CRAWL_RESERVED',
                    'value' => 'n'
                ]);
                #endregion
            });

        #endregion

        #region Alert.php

        $schedule->command('alert')
            ->withoutOverlapping()
            ->everyThirtyMinutes();

        #endregion
    }
}
