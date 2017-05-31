<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Crawler;
use App\Models\Item;
use App\Models\Product;
use App\Models\Site;
use App\Models\Url;
use App\Models\User;
use App\Observers\CategoryObserver;
use App\Observers\CrawlerObserver;
use App\Observers\ItemObserver;
use App\Observers\ProductObserver;
use App\Observers\SiteObserver;
use App\Observers\UrlObserver;
use App\Observers\UserObserver;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /*region observers*/
        User::observe(UserObserver::class);
        Category::observe(CategoryObserver::class);
        Product::observe(ProductObserver::class);
        Site::observe(SiteObserver::class);
        Url::observe(UrlObserver::class);
        Item::observe(ItemObserver::class);
        Crawler::observe(CrawlerObserver::class);
        /*endregion*/

        /*region morph*/
        Relation::morphMap([
            'category' => Category::class,
            'product' => Product::class,
            'site' => Site::class,
        ]);
        /*endregion*/
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Contracts\Repositories\Admin\AppPrefContract', 'App\Repositories\Admin\AppPrefRepository');
        $this->app->bind('App\Contracts\Repositories\Admin\UserActivityLogContract', 'App\Repositories\Admin\UserActivityLogRepository');

        $this->app->bind('App\Contracts\Repositories\Dashboard\WidgetContract', 'App\Repositories\Dashboard\WidgetRepository');

        $this->app->bind('App\Contracts\Repositories\Product\CategoryContract', 'App\Repositories\Product\CategoryRepository');
        $this->app->bind('App\Contracts\Repositories\Product\ProductContract', 'App\Repositories\Product\ProductRepository');
        $this->app->bind('App\Contracts\Repositories\Product\SiteContract', 'App\Repositories\Product\SiteRepository');

        $this->app->bind('App\Contracts\Repositories\Alert\AlertContract', 'App\Repositories\Alert\AlertRepository');
        $this->app->bind('App\Contracts\Repositories\Alert\HistoricalAlertContract', 'App\Repositories\Alert\HistoricalAlertRepository');
        $this->app->bind('App\Contracts\Repositories\Report\ReportContract', 'App\Repositories\Report\ReportRepository');
        $this->app->bind('App\Contracts\Repositories\Report\HistoricalReportContract', 'App\Repositories\Report\HistoricalReportRepository');

        $this->app->bind('App\Contracts\Repositories\UrlManagement\DomainContract', 'App\Repositories\UrlManagement\DomainRepository');
        $this->app->bind('App\Contracts\Repositories\UrlManagement\DomainConfContract', 'App\Repositories\UrlManagement\DomainConfRepository');
        $this->app->bind('App\Contracts\Repositories\UrlManagement\DomainMetaContract', 'App\Repositories\UrlManagement\DomainMetaRepository');
        $this->app->bind('App\Contracts\Repositories\UrlManagement\ItemContract', 'App\Repositories\UrlManagement\ItemRepository');
        $this->app->bind('App\Contracts\Repositories\UrlManagement\ItemMetaContract', 'App\Repositories\UrlManagement\ItemMetaRepository');
        $this->app->bind('App\Contracts\Repositories\UrlManagement\ItemMetaConfContract', 'App\Repositories\UrlManagement\ItemMetaConfRepository');
        $this->app->bind('App\Contracts\Repositories\UrlManagement\UrlContract', 'App\Repositories\UrlManagement\UrlRepository');
        $this->app->bind('App\Contracts\Repositories\UrlManagement\CrawlerContract', 'App\Repositories\UrlManagement\CrawlerRepository');
        $this->app->bind('App\Contracts\Repositories\UrlManagement\ParserContract', 'App\Repositories\UrlManagement\ParserRepository');

        $this->app->bind('App\Contracts\Repositories\UserManagement\UserContract', 'App\Repositories\UserManagement\UserRepository');
        $this->app->bind('App\Contracts\Repositories\UserManagement\GroupContract', 'App\Repositories\UserManagement\GroupRepository');
        $this->app->bind('App\Contracts\Repositories\UserManagement\RoleContract', 'App\Repositories\UserManagement\RoleRepository');
        $this->app->bind('App\Contracts\Repositories\UserManagement\PermissionContract', 'App\Repositories\UserManagement\PermissionRepository');


        $this->app->bind('App\Contracts\Repositories\API\TokenContract', 'App\Repositories\API\TokenRepository');
    }
}
