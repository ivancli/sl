<?php

/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 18/03/2017
 * Time: 2:00 PM
 */
namespace App\Jobs\Log;

use App\Contracts\Repositories\Admin\UserActivityLogContract;
use App\Contracts\Repositories\UrlManagement\CrawlerContract;
use App\Contracts\Repositories\UrlManagement\ParserContract;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UserActivity implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $activity;

    /**
     * Create a new job instance.
     * @param User $user
     * @param String $activity
     */
    public function __construct(User $user, String $activity)
    {
        $this->user = $user;
        $this->activity = $activity;
    }

    /**
     * Execute the job.
     * @param UserActivityLogContract $userActivityLogRepo
     */
    public function handle(UserActivityLogContract $userActivityLogRepo)
    {
        $activity = $this->activity;
        $userActivityLog = $userActivityLogRepo->store(compact(['activity']));
        $this->user->activityLogs()->save($userActivityLog);
    }
}
