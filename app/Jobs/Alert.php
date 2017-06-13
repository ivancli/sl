<?php

namespace App\Jobs;

use App\Contracts\Repositories\Alert\HistoricalAlertContract;
use App\Mail\Alert\AdvancedCategoryMyPrice;
use App\Mail\Alert\AdvancedCategoryPriceChange;
use App\Mail\Alert\AdvancedProductCustom;
use App\Mail\Alert\AdvancedProductMyPrice;
use App\Mail\Alert\AdvancedProductPriceChange;
use App\Mail\Alert\BasicMyPrice;
use App\Mail\Alert\BasicPriceChange;
use App\Models\Alert as AlertModel;

use App\Models\Site;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class Alert implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $alert;
    protected $lastActiveAt = null;
    protected $alertCreatedAt = null;
    protected $historicalAlertRepo;
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
            $this->lastActiveAt = Carbon::parse($this->alert->last_active_at);
        }
        if (!is_null($this->alert->created_at)) {
            $this->alertCreatedAt = Carbon::parse($this->alert->created_at);
        }
        $this->historicalAlertRepo = app(HistoricalAlertContract::class);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (!$this->_validateSubscription($this->alert)) {
            return;
        }

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
        switch ($this->alert->alertable_type) {
            case 'product':
                switch ($this->alert->comp_type) {
                    case 'my_price':
                        $this->_processAdvancedProductMyPrice();
                        break;
                    case 'price_change':
                        $this->_processAdvancedProductPriceChange();
                        break;
                    case 'custom':
                        $this->_processAdvancedProductCustom();
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

    /**
     * validate advanced alert - product my price is beaten
     * @return bool
     */
    private function _processAdvancedProductMyPrice()
    {
        $product = $this->alert->alertable;

        $userDomains = $this->user->domains->pluck('alias', 'domain')->all();

        if (is_null($product)) {
            return false;
        }
        $sites = $product->sites;

        $mySites = $sites->filter(function ($site) {
            return $this->_isMySite($site);
        });
        $notMySites = $sites->filter(function ($site) {
            return !$this->_isMySite($site);
        });

        if ($mySites->count() == 0 || $notMySites->count() == 0) {
            return false;
        }

        $beatenBySites = collect();
        foreach ($mySites as $mySite) {

            $mySitePriceHasChanged = $this->_siteHasPriceChange($mySite);

            $mySiteItem = $mySite->item;

            if (is_null($mySiteItem)) {
                continue;
            }
            $lastChangedAt = null;
            if (!is_null($mySiteItem->lastChangedAt)) {
                $lastChangedAt = Carbon::parse($mySiteItem->lastChangedAt);
            }
            foreach ($notMySites as $notMySite) {
                if ($this->_siteHasPriceChange($notMySite) || $mySitePriceHasChanged) {

                    $notMySiteItem = $notMySite->item;

                    if (is_null($notMySiteItem)) {
                        continue;
                    }

                    $notMySiteLastChangedAt = null;
                    if (!is_null($notMySiteItem->lastChangedAt)) {
                        $notMySiteLastChangedAt = Carbon::parse($notMySiteItem->lastChangedAt);
                    }

                    $comparedDateTime = !is_null($this->lastActiveAt) ? $this->lastActiveAt : $this->alertCreatedAt;

                    /* either my site or not my site has changed price */
                    if ((!is_null($lastChangedAt) && $lastChangedAt > $comparedDateTime) || (!is_null($notMySiteLastChangedAt) && $notMySiteLastChangedAt > $comparedDateTime)) {
                        /* both my site and not my site have recent prices */
                        if (!is_null($mySiteItem->recentPrice) && !is_null($notMySiteItem->recentPrice)) {
                            if (floatval($notMySiteItem->recentPrice) < floatval($mySiteItem->recentPrice)) {

                                $siteDomain = domain($notMySite->siteUrl);

                                if (!is_null($notMySite->item) && !is_null($notMySite->item->sellerUsername)) {
                                    $notMySite->setAttribute('displayName', "eBay: {$notMySite->item->sellerUsername}");
                                } elseif (array_has($userDomains, $siteDomain) && !is_null(array_get($userDomains, $siteDomain))) {
                                    $notMySite->setAttribute('displayName', array_get($userDomains, $siteDomain));
                                } else {
                                    $notMySite->setAttribute('displayName', $notMySite->url->domainFullPath);
                                }

                                $beatenBySites->push($notMySite);
                            }
                        }
                    }
                }
            }
        }
        if ($beatenBySites->count() > 0) {
            $this->alert->setLastActiveAt();

            $email = $this->user->email;

            $this->historicalAlertRepo->store($this->alert, compact(['email']));

            /* TODO dispatch mail job with beatenBySites */
            Mail::to($email)
                ->send(new AdvancedProductMyPrice($this->user, $product));
        }

        return true;
    }

    /**
     * validate advanced alert - product price change alert
     * @return bool
     */
    private function _processAdvancedProductPriceChange()
    {
        $product = $this->alert->alertable;

        $userDomains = $this->user->domains->pluck('alias', 'domain')->all();

        if (is_null($product)) {
            return false;
        }

        $sites = $product->sites;

        $alertSites = collect();
        foreach ($sites as $site) {
            if ($this->_siteHasPriceChange($site)) {

                $siteDomain = domain($site->siteUrl);

                if (!is_null($site->item) && !is_null($site->item->sellerUsername)) {
                    $site->setAttribute('displayName', "eBay: {$site->item->sellerUsername}");
                } elseif (array_has($userDomains, $siteDomain) && !is_null(array_get($userDomains, $siteDomain))) {
                    $site->setAttribute('displayName', array_get($userDomains, $siteDomain));
                } else {
                    $site->setAttribute('displayName', $site->url->domainFullPath);
                }

                $alertSites->push($site);
            }
        }
        if ($alertSites->count() > 0) {
            $this->alert->setLastActiveAt();

            $email = $this->user->email;

            $this->historicalAlertRepo->store($this->alert, compact(['email']));

            /* TODO dispatch mail job with $alertSites */
            Mail::to($email)
                ->send(new AdvancedProductPriceChange($this->user, $product, $alertSites));
        }

        return true;
    }

    /**
     * validate advanced alert - product custom validation alert
     * @return bool
     */
    private function _processAdvancedProductCustom()
    {
        $product = $this->alert->alertable;

        $userDomains = $this->user->domains->pluck('alias', 'domain')->all();

        if (is_null($product)) {
            return false;
        }
        $sites = $product->sites;

        $alertSites = collect();
        foreach ($sites as $site) {

            if ($this->_siteHasPriceChange($site)) {

                $siteItem = $site->item;

                if (is_null($siteItem)) {
                    continue;
                }

                $siteLastChangedAt = null;
                if (!is_null($siteItem->lastChangedAt)) {
                    $siteLastChangedAt = Carbon::parse($siteItem->lastChangedAt);
                }

                $comparedDateTime = !is_null($this->lastActiveAt) ? $this->lastActiveAt : $this->alertCreatedAt;

                if (!is_null($siteLastChangedAt) && $siteLastChangedAt > $comparedDateTime) {
                    if (!is_null($siteItem->recentPrice)) {

                        $comparedResult = null;
                        $comparedPrice = $this->alert->comp_price;
                        switch ($this->alert->comp_operator) {
                            case '<':
                                $comparedResult = floatval($siteItem->recentPrice) < $comparedPrice;
                                break;
                            case '<=':
                                $comparedResult = floatval($siteItem->recentPrice) <= $comparedPrice;
                                break;
                            case '>':
                                $comparedResult = floatval($siteItem->recentPrice) > $comparedPrice;
                                break;
                            case '>=':
                                $comparedResult = floatval($siteItem->recentPrice) >= $comparedPrice;
                                break;
                            case '=':
                                $comparedResult = floatval($siteItem->recentPrice) == $comparedPrice;
                                break;
                        }
                        if ($comparedResult) {

                            $siteDomain = domain($site->siteUrl);

                            if (!is_null($site->item) && !is_null($site->item->sellerUsername)) {
                                $site->setAttribute('displayName', "eBay: {$site->item->sellerUsername}");
                            } elseif (array_has($userDomains, $siteDomain) && !is_null(array_get($userDomains, $siteDomain))) {
                                $site->setAttribute('displayName', array_get($userDomains, $siteDomain));
                            } else {
                                $site->setAttribute('displayName', $site->url->domainFullPath);
                            }

                            $alertSites->push($site);
                        }
                    }
                }
            }
        }
        if ($alertSites->count() > 0) {
            $this->alert->setLastActiveAt();

            $email = $this->user->email;

            $this->historicalAlertRepo->store($this->alert, compact(['email']));

            /* TODO dispatch mail job with beatenBySites */
            Mail::to($email)
                ->send(new AdvancedProductCustom($this->user, $this->alert, $alertSites));
        }

        return true;
    }

    /**
     * validate advanced alert - category my price beaten alert
     * @return bool
     */
    private function _processAdvancedCategoryMyPrice()
    {
        $category = $this->alert->alertable;

        if (is_null($category)) {
            return false;
        }

        $comparedDateTime = !is_null($this->lastActiveAt) ? $this->lastActiveAt : $this->alertCreatedAt;
        $cancelled_at = null;
        $type = "enterprise";

        if (!is_null($this->user->subscription)) {
            if (!is_null($this->user->subscription->cancelled_at)) {
                $cancelled_at = $this->user->subscription->cancelled_at;
            }
            if (!is_null($this->user->subscription->subscriptionPlan)) {
                $type = $this->user->subscription->subscriptionPlan->handle;
            }
        }

        $query = DB::table('products')
            ->join('categories', 'categories.id', 'products.category_id')
            ->join('my_sites', 'products.id', 'my_sites.product_id')
            ->join('items AS my_items', 'my_sites.item_id', 'my_items.id')
            ->join('item_metas AS my_item_metas', function ($join) {
                $join->on('my_item_metas.item_id', 'my_items.id')
                    ->where('my_item_metas.element', 'PRICE');
            })
            ->join('sites', function ($join) {
                $join->on('products.id', 'sites.product_id')
                    ->on('my_sites.id', '!=', 'sites.id');
            })
            ->join('items AS site_items', 'site_items.id', 'sites.item_id')
            ->join('item_metas AS site_item_metas', function ($join) {
                $join->on('site_item_metas.item_id', 'site_items.id')
                    ->where('site_item_metas.element', 'PRICE');
            });
        $addHour = 1;
        switch ($type) {
            case 'professional':
                $addHour = 12;
                $query->leftJoin('previous_price_professional AS my_previous_price', 'my_previous_price.item_meta_id', 'my_item_metas.id');
                $query->leftJoin('previous_price_professional AS sites_previous_price', 'sites_previous_price.item_meta_id', 'site_item_metas.id');
                break;
            case 'business':
                $addHour = 4;
                $query->leftJoin('previous_price_business AS my_previous_price', 'my_previous_price.item_meta_id', 'my_item_metas.id');
                $query->leftJoin('previous_price_business AS sites_previous_price', 'sites_previous_price.item_meta_id', 'site_item_metas.id');
                break;
            case 'enterprise':
            default:
                $query->leftJoin('previous_price_enterprise AS my_previous_price', 'my_previous_price.item_meta_id', 'my_item_metas.id');
                $query->leftJoin('previous_price_enterprise AS sites_previous_price', 'sites_previous_price.item_meta_id', 'site_item_metas.id');
        }


        $query->select([
            'products.*',
            'products.product_name',
            'categories.category_name',
        ]);
        $query->where('products.category_id', $category->getKey());
        $query->where(function ($query) use ($comparedDateTime, $cancelled_at, $addHour) {
            $query->where(function ($query) use ($comparedDateTime, $cancelled_at, $addHour) {
                $query->whereNotNull('sites_previous_price.created_at')
                    ->where("DATE_ADD(sites_previous_price.created_at, INTERVAL +{$addHour} HOUR)", '>', $comparedDateTime->format('Y-m-d H:i:s'));
                if (!is_null($cancelled_at)) {
                    $query->where("DATE_ADD(sites_previous_price.created_at, INTERVAL +{$addHour} HOUR)", '<', $cancelled_at);
                }
            })
                ->orWhere(function ($query) use ($comparedDateTime, $cancelled_at, $addHour) {
                    $query->whereNotNull('my_previous_price.created_at')
                        ->where("DATE_ADD(my_previous_price.created_at, INTERVAL +{$addHour} HOUR)", '>', $comparedDateTime->format('Y-m-d H:i:s'));
                    if (!is_null($cancelled_at)) {
                        $query->where("DATE_ADD(my_previous_price.created_at, INTERVAL +{$addHour} HOUR)", '<', $cancelled_at);
                    }
                });
        });
        $query->where(DB::raw('CAST(site_item_metas.value AS DECIMAL(10, 4))'), '<', DB::raw('CAST(my_item_metas.value AS DECIMAL(10, 4))'));
        $query->groupBy('products.id');

        $alertProducts = $query->get();

        if ($alertProducts->count() > 0) {
            $this->alert->setLastActiveAt();

            $email = $this->user->email;

            $this->historicalAlertRepo->store($this->alert, compact(['email']));

            /* TODO dispatch mail job with $alertProducts */
            Mail::to($email)
                ->send(new AdvancedCategoryMyPrice($this->user, $category, $alertProducts));
        }


        return true;
    }

    /**
     * validate advanced alert - category price change alert
     * @return bool
     */
    private function _processAdvancedCategoryPriceChange()
    {
        $category = $this->alert->alertable;

        $userDomains = $this->user->domains->pluck('alias', 'domain')->all();

        if (is_null($category)) {
            return false;
        }

        $comparedDateTime = !is_null($this->lastActiveAt) ? $this->lastActiveAt : $this->alertCreatedAt;
        $cancelled_at = null;
        $type = "enterprise";

        if (!is_null($this->user->subscription)) {
            if (!is_null($this->user->subscription->cancelled_at)) {
                $cancelled_at = $this->user->subscription->cancelled_at;
            }
            if (!is_null($this->user->subscription->subscriptionPlan)) {
                $type = $this->user->subscription->subscriptionPlan->handle;
            }
        }

        $query = DB::table('sites')
            ->join('products', 'sites.product_id', 'products.id')
            ->join('categories', 'products.category_id', 'categories.id')
            ->join('users', 'users.id', 'products.user_id')
            ->join('items', 'sites.item_id', 'items.id')
            ->join('urls', 'urls.id', 'sites.url_id')
            ->join('item_metas AS price', function ($join) {
                $join->on('price.item_id', 'items.id')
                    ->where('price.element', 'PRICE');
            })
            ->leftJoin('item_metas AS ebay', function ($join) {
                $join->on('ebay.item_id', 'items.id')
                    ->where('ebay.element', 'SELLER_USERNAME');
            })
            ->leftJoin('user_domains', function ($join) {
                $join->on('users.id', 'user_domains.user_id')
                    ->where('user_domains.domain', 'LIKE', DB::raw('CONCAT("%", urls.full_path, "%")'));
            })
            ->where('products.category_id', $category->getKey());

        switch ($type) {
            case 'professional':
                $query->join('previous_prices_professional AS previous_price', 'previous_price.item_meta_id', 'price.id');
                break;
            case 'business':
                $query->join('previous_prices_business AS previous_price', 'previous_price.item_meta_id', 'price.id');
                break;
            case 'enterprise':
            default:
                $query->join('previous_prices_enterprise AS previous_price', 'previous_price.item_meta_id', 'price.id');
        }

        $query->select([
            'sites.*',
            'price.value AS recent_price',
            'previous_price.amount AS previous_price',
            'previous_price_changes.created_at AS previous_price_changed_at',
            'ebay.value AS ebay_username',
            'user_domains.alias AS site_name',
            'urls.full_path AS url',
            'products.product_name',
            'categories.category_name',
            DB::raw("SUBSTRING_INDEX(REPLACE(REPLACE(REPLACE(urls.full_path,'http://',''),'https://',''),'www.',''),'/',1) AS domain"),
            DB::raw("IFNULL(ebay.value, IFNULL(user_domains.alias, SUBSTRING_INDEX(REPLACE(REPLACE(REPLACE(urls.full_path,'http://',''),'https://',''),'www.',''),'/',1))) AS display_name")
        ]);

        if (!is_null($cancelled_at)) {
            $query->where('previous_price_changes.created_at', '<', $cancelled_at);
        }
        $query->where('previous_price_changes.created_at', '>', $comparedDateTime->format('Y-m-d H:i:s'));
        $alertSites = $query->get();
        if ($alertSites->count() > 0) {
            $this->alert->setLastActiveAt();

            $email = $this->user->email;

            $this->historicalAlertRepo->store($this->alert, compact(['email']));

            Mail::to($email)
                ->send(new AdvancedCategoryPriceChange($this->user, $category, $alertSites));
        }

        return true;
    }

    /**
     * validate basic alert - my price beaten alert
     * @return bool
     */
    private function _processBasicMyPrice()
    {
        $comparedDateTime = !is_null($this->lastActiveAt) ? $this->lastActiveAt : $this->alertCreatedAt;
        $cancelled_at = null;
        $type = "enterprise";

        if (!is_null($this->user->subscription)) {
            if (!is_null($this->user->subscription->cancelled_at)) {
                $cancelled_at = $this->user->subscription->cancelled_at;
            }
            if (!is_null($this->user->subscription->subscriptionPlan)) {
                $type = $this->user->subscription->subscriptionPlan->handle;
            }
        }

        $query = DB::table('products')
            ->join('categories', 'categories.id', 'products.category_id')
            ->join('my_sites', 'products.id', 'my_sites.product_id')
            ->join('items AS my_items', 'my_sites.item_id', 'my_items.id')
            ->join('item_metas AS my_item_metas', function ($join) {
                $join->on('my_item_metas.item_id', 'my_items.id')
                    ->where('my_item_metas.element', 'PRICE');
            })
            ->join('sites', function ($join) {
                $join->on('products.id', 'sites.product_id')
                    ->on('my_sites.id', '!=', 'sites.id');
            })
            ->join('items AS site_items', 'site_items.id', 'sites.item_id')
            ->join('item_metas AS site_item_metas', function ($join) {
                $join->on('site_item_metas.item_id', 'site_items.id')
                    ->where('site_item_metas.element', 'PRICE');
            });
        $addHour = 1;
        switch ($type) {
            case 'professional':
                $addHour = 12;
                $query->leftJoin('previous_price_professional AS my_previous_price', 'my_previous_price.item_meta_id', 'my_item_metas.id');
                $query->leftJoin('previous_price_professional AS sites_previous_price', 'sites_previous_price.item_meta_id', 'site_item_metas.id');
                break;
            case 'business':
                $addHour = 4;
                $query->leftJoin('previous_price_business AS my_previous_price', 'my_previous_price.item_meta_id', 'my_item_metas.id');
                $query->leftJoin('previous_price_business AS sites_previous_price', 'sites_previous_price.item_meta_id', 'site_item_metas.id');
                break;
            case 'enterprise':
            default:
                $query->leftJoin('previous_price_enterprise AS my_previous_price', 'my_previous_price.item_meta_id', 'my_item_metas.id');
                $query->leftJoin('previous_price_enterprise AS sites_previous_price', 'sites_previous_price.item_meta_id', 'site_item_metas.id');
        }


        $query->select([
            'products.*',
            'products.product_name',
            'categories.category_name',
        ]);
        $query->where(function ($query) use ($comparedDateTime, $cancelled_at, $addHour) {
            $query->where(function ($query) use ($comparedDateTime, $cancelled_at, $addHour) {
                $query->whereNotNull('sites_previous_price.created_at')
                    ->where("DATE_ADD(sites_previous_price.created_at, INTERVAL +{$addHour} HOUR)", '>', $comparedDateTime->format('Y-m-d H:i:s'));
                if (!is_null($cancelled_at)) {
                    $query->where("DATE_ADD(sites_previous_price.created_at, INTERVAL +{$addHour} HOUR)", '<', $cancelled_at);
                }
            })
                ->orWhere(function ($query) use ($comparedDateTime, $cancelled_at, $addHour) {
                    $query->whereNotNull('my_previous_price.created_at')
                        ->where("DATE_ADD(my_previous_price.created_at, INTERVAL +{$addHour} HOUR)", '>', $comparedDateTime->format('Y-m-d H:i:s'));
                    if (!is_null($cancelled_at)) {
                        $query->where("DATE_ADD(my_previous_price.created_at, INTERVAL +{$addHour} HOUR)", '<', $cancelled_at);
                    }
                });
        });
        $query->where(DB::raw('CAST(site_item_metas.value AS DECIMAL(10, 4))'), '<', DB::raw('CAST(my_item_metas.value AS DECIMAL(10, 4))'));
        $query->where('products.user_id', $this->user->getKey());
        $query->groupBy('products.id');

        $alertProducts = $query->get();

        if ($alertProducts->count() > 0) {
            $this->alert->setLastActiveAt();

            $email = $this->user->email;

            $this->historicalAlertRepo->store($this->alert, compact(['email']));

            /* TODO dispatch mail job with $alertProducts */
            Mail::to($email)
                ->send(new BasicMyPrice($this->user, $alertProducts));
        }

        return true;
    }

    /**
     * validate basic alert - price change alert
     * @return bool
     */
    private function _processBasicPriceChange()
    {
        $comparedDateTime = !is_null($this->lastActiveAt) ? $this->lastActiveAt : $this->alertCreatedAt;
        $cancelled_at = null;
        $type = "enterprise";

        if (!is_null($this->user->subscription)) {
            if (!is_null($this->user->subscription->cancelled_at)) {
                $cancelled_at = $this->user->subscription->cancelled_at;
            }
            if (!is_null($this->user->subscription->subscriptionPlan)) {
                $type = $this->user->subscription->subscriptionPlan->handle;
            }
        }

        $query = DB::table('sites')
            ->join('products', 'sites.product_id', 'products.id')
            ->join('categories', 'products.category_id', 'categories.id')
            ->join('users', 'users.id', 'products.user_id')
            ->join('items', 'sites.item_id', 'items.id')
            ->join('urls', 'urls.id', 'sites.url_id')
            ->join('item_metas AS price', function ($join) {
                $join->on('price.item_id', 'items.id')
                    ->where('price.element', 'PRICE');
            })
            ->leftJoin('item_metas AS ebay', function ($join) {
                $join->on('ebay.item_id', 'items.id')
                    ->where('ebay.element', 'SELLER_USERNAME');
            })
            ->leftJoin('user_domains', function ($join) {
                $join->on('users.id', 'user_domains.user_id')
                    ->where('user_domains.domain', 'LIKE', DB::raw('CONCAT("%", urls.full_path, "%")'));
            })
            ->where('products.user_id', $this->user->getKey());

        $addHour = 1;
        switch ($type) {
            case 'professional':
                $addHour = 12;
                $query->join('previous_prices_professional AS previous_price', function ($join) use ($cancelled_at) {
                    $join->on('previous_price.item_meta_id', 'price.id');
                    if (!is_null($cancelled_at)) {
                        $join->where('previous_price_changes.created_at', '<', $cancelled_at);
                    }
                });
                break;
            case 'business':
                $addHour = 4;
                $query->join('previous_prices_business AS previous_price', function ($join) use ($cancelled_at) {
                    $join->on('previous_price.item_meta_id', 'price.id');
                    if (!is_null($cancelled_at)) {
                        $join->where('previous_price_changes.created_at', '<', $cancelled_at);
                    }
                });
                break;
            case 'enterprise':
            default:
                $query->join('previous_prices_enterprise AS previous_price', function ($join) use ($cancelled_at) {
                    $join->on('previous_price.item_meta_id', 'price.id');
                    if (!is_null($cancelled_at)) {
                        $join->where('previous_price_changes.created_at', '<', $cancelled_at);
                    }
                });
        }

        $query->select([
            'sites.*',
            'price.value AS recent_price',
            'previous_price.amount AS previous_price',
            'ebay.value AS ebay_username',
            'user_domains.alias AS site_name',
            'urls.full_path AS url',
            'products.product_name',
            'categories.category_name',
            DB::raw("DATE_ADD(previous_price.created_at, INTERVAL +{$addHour} HOUR) AS previous_price_changed_at")
        ]);
        $query->where("DATE_ADD(previous_price.created_at, INTERVAL +{$addHour} HOUR)", '>', $comparedDateTime->format('Y-m-d H:i:s'));
        $alertSites = $query->get();
        if ($alertSites->count() > 0) {
            $this->alert->setLastActiveAt();

            $email = $this->user->email;

            $this->historicalAlertRepo->store($this->alert, compact(['email']));

            Mail::to($email)
                ->send(new BasicPriceChange($this->user, $alertSites));
        }

        return true;
    }

    /**
     * check if provided site is my site
     * @param Site $site
     * @return bool
     */
    private function _isMySite(Site $site)
    {
        $ebaySellerUsername = $this->user->metas->ebay_username;
        $companyUrl = $this->user->metas->company_url;

        /*ebay username checking*/
        if (!is_null($site->item) && !is_null($site->item->sellerUsername)) {
            if ($site->item->sellerUsername == $ebaySellerUsername) {
                return true;
            }
        }

        /*company url checking*/
        if (!is_null($companyUrl)) {
            $urlSegments = parse_url($companyUrl);
            $companyDomain = array_get($urlSegments, 'host', '');

            if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $companyDomain, $regs)) {
                $companyDomain = array_get($regs, 'domain');
            } else {
                return false;
            }

            $siteDomainSegments = parse_url($site->url->domainFullPath);
            $siteDomain = array_get($siteDomainSegments, 'host', '');

            if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $siteDomain, $regs)) {
                $siteDomain = array_get($regs, 'domain');
            } else {
                return false;
            }
            return $companyDomain == $siteDomain;
        }

        return false;
    }

    /**
     * check if provided site has recently changed price after alert last active/created
     * @param Site $site
     * @return bool
     */
    private function _siteHasPriceChange(Site $site)
    {
        $item = $site->item;
        if (!is_null($item)) {
            if (!is_null($item->lastChangedAt)) {
                $lastChangedAt = Carbon::parse($item->lastChangedAt);
                if (!is_null($this->lastActiveAt)) {
                    if ($lastChangedAt > $this->lastActiveAt) {
                        return true;
                    }
                } else {
                    if ($lastChangedAt > $this->alertCreatedAt) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    private function _validateSubscription(AlertModel $alert)
    {
        $user = $alert->user;
        if (!is_null($user->subscription)) {
            return $user->subscription->isValid;
        }
        return true;
    }
}
