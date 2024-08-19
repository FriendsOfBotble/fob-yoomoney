<?php

namespace FriendsOfBotble\Yoomoney\Providers;

use Botble\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Support\ServiceProvider;

class YoomoneyServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot(): void
    {
        if (! is_plugin_active('payment')) {
            return;
        }

        if (! is_plugin_active('ecommerce') && ! is_plugin_active('job-board')) {
            return;
        }

        $this->setNamespace('plugins/yoomoney')
            ->loadHelpers()
            ->loadRoutes()
            ->loadAndPublishViews()
            ->publishAssets();

        $this->app->register(HookServiceProvider::class);
    }
}
