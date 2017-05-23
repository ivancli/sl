<?php

namespace App\Jobs;

use App\Models\Alert as AlertModel;

use App\Models\Site;
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
    protected $alertCreatedAt = null;
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
        if (!is_null($this->alert->created_at)) {
            $this->alertCreatedAt = Carbon::createFromFormat('Y-m-d H:i:s', $this->alert->created_at);
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
            case 'basic':
                $this->processBasicAlert();
                break;
            case 'advanced':
                $this->processAdvancedAlert();
                break;

        }

        /*TODO if it's within crawling frequency, ignore*/

        /*TODO if it's outside crawling frequency, trigger alert email*/
    }

    protected function processBasicAlert()
    {
        switch ($this->alert->comp_type) {
            case 'my_price':
                $this->_processBasicMyPrice();
                break;
            case 'price_change':
                $this->_processBasicPriceChange();
                break;
        }
    }

    protected function processAdvancedAlert()
    {
        $alertable = $this->alert->alertable;

        switch ($this->alert->alertable_type) {
            case 'product':
                switch ($this->alert->comp_type) {
                    case 'my_price':
                        $this->_processAdvancedProductMyPrice();
                        break;
                    case 'price_change':
                        $this->_processAdvancedProductPriceChange();
                        break;
                }
                break;
            case 'category':
                switch ($this->alert->comp_type) {
                    case 'my_price':
                        $this->_processAdvancedCategoryMyPrice();
                        break;
                    case 'price_change':
                        $this->_processAdvancedCategoryPriceChange();
                        break;
                }
                break;
        }
    }

    private function _processAdvancedProductPriceChange()
    {

    }

    private function _processAdvancedProductMyPrice()
    {

    }

    private function _processAdvancedCategoryPriceChange()
    {

    }

    private function _processAdvancedCategoryMyPrice()
    {

    }

    private function _processBasicMyPrice()
    {
        $products = $this->user->products;

        $alertProducts = collect();
        foreach ($products as $product) {
            $sites = $product->sites;
            $mySites = $sites->filter(function ($site) {
                return $this->_isMySite($site);
            });
            if ($mySites->count() == 0) {
                continue;
            }
            $notMySites = $sites->filter(function ($site) {
                return !$this->_isMySite($site);
            });
            $beatenBySites = collect();
            foreach ($mySites as $mySite) {

                $mySiteItem = $mySite->item;

                if (is_null($mySiteItem)) {
                    continue;
                }

                $lastChangedAt = null;
                if (!is_null($mySiteItem->lastChangedAt)) {
                    $lastChangedAt = Carbon::createFromFormat('Y-m-d H:i:s', $mySiteItem->lastChangedAt);
                }

                foreach ($notMySites as $notMySite) {
                    $notMySiteItem = $notMySite->item;

                    if (is_null($notMySiteItem)) {
                        continue;
                    }

                    $notMySiteLastChangedAt = null;
                    if (!is_null($notMySiteItem->lastChangedAt)) {
                        $notMySiteLastChangedAt = Carbon::createFromFormat('Y-m-d H:i:s', $notMySiteItem->lastChangedAt);
                    }

                    /* either my site or not my site has changed price */
                    if ((!is_null($lastChangedAt) && $lastChangedAt > $this->lastActiveAt) || (!is_null($notMySiteLastChangedAt) && $notMySiteLastChangedAt > $this->lastActiveAt)) {
                        /* both my site and not my site have recent prices */
                        if (!is_null($mySiteItem->recentPrice) && !is_null($notMySiteItem->recentPrice)) {
                            if ($notMySiteItem->recentPrice > $mySiteItem->recentPrice) {
                                $beatenBySites->push($notMySite);
                            }
                        }
                    }
                }
            }
            if ($beatenBySites->count() > 0) {
                $alertProducts->push($product);
            }
        }

        if ($alertProducts->count() > 0) {
            $this->alert->setLastActiveAt();

            /* TODO dispatch mail job with $alertProducts */
        }
    }

    private function _processBasicPriceChange()
    {
        $sites = $this->user->sites;

        $alertSites = collect();
        foreach ($sites as $site) {
            $item = $site->item;
            if (!is_null($item)) {
                if (!is_null($item->lastChangedAt)) {
                    $lastChangedAt = Carbon::createFromFormat('Y-m-d H:i:s', $item->lastChangedAt);
                    if (!is_null($this->lastActiveAt)) {
                        if ($lastChangedAt > $this->lastActiveAt) {
                            $alertSites->push($site);
                        }
                    } else {
                        if ($lastChangedAt > $this->alertCreatedAt) {
                            $alertSites->push($site);
                        }
                    }
                }
            }
        }
        if ($alertSites->count() > 0) {
            $this->alert->setLastActiveAt();

            /* TODO dispatch mail job with $alertSites */
        }

    }

    private function _isMySite(Site $site)
    {
        $companyUrl = $this->user->company_url;
        if (!is_null($companyUrl)) {
            $urlSegments = parse_url($companyUrl);
            $companyDomain = isset($urlSegments['host']) ? $urlSegments['host'] : '';

            if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $companyDomain, $regs)) {
                $companyDomain = $regs['domain'];
            } else {
                return false;
            }

            $siteDomainSegments = parse_url($site->url->domain);
            $siteDomain = isset($siteDomainSegments['host']) ? $siteDomainSegments['host'] : '';

            if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $siteDomain, $regs)) {
                $siteDomain = $regs['domain'];
            } else {
                return false;
            }
            return $companyDomain == $siteDomain;
        }

        /* TODO add ebay later on */

        return false;
    }
}
