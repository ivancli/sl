<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 19/04/2017
 * Time: 10:35 PM
 */

namespace App\Observers;


use App\Contracts\Repositories\UrlManagement\UrlContract;
use App\Contracts\Repositories\UserManagement\UserContract;
use App\Models\Site;
use App\Services\MailingAgent\CampaignMonitor\MailingAgentService;

class SiteObserver
{
    protected $urlRepo;
    protected $userRepo;

    protected $mailingAgentService;

    public function __construct(UrlContract $urlContract, UserContract $userContract, MailingAgentService $mailingAgentService)
    {
        $this->urlRepo = $urlContract;
        $this->userRepo = $userContract;

        $this->mailingAgentService = $mailingAgentService;
    }

    public function creating()
    {

    }

    public function created(Site $site)
    {

    }

    public function saving()
    {

    }

    public function saved(Site $site)
    {
        #region create user domain alias preference
        if ($site->product()->count() > 0) {
            $product = $site->product;
            $user = $product->user;

            $domains = [];
            $domainFullPath = domain($site->siteUrl);
            if (!is_null($domainFullPath)) {
                $domains[] = [
                    'domain' => $domainFullPath,
                ];
                $this->userRepo->updateUserDomains($user, $domains);
            }
        }
        #endregion
    }

    public function updating(Site $site)
    {

    }

    public function updated(Site $site)
    {

    }

    public function deleting(Site $site)
    {
        $site->widgets()->delete();
    }

    public function deleted()
    {

    }

    public function restoring()
    {

    }

    public function restored(Site $site)
    {

    }
}