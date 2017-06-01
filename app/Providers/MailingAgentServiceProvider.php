<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class MailingAgentServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Contracts\Repositories\MailingAgent\MailingAgentContract', 'App\Repositories\MailingAgent\CampaignMonitor\MailingAgentRepository');
    }
}
