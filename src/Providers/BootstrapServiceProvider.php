<?php

namespace Consigliere\Components\Providers;

use Illuminate\Support\ServiceProvider;

class BootstrapServiceProvider extends ServiceProvider
{
    /**
     * Booting the package.
     */
    public function boot()
    {
        $this->app['components']->boot();
    }

    /**
     * Register the provider.
     */
    public function register()
    {
        $this->app['components']->register();
    }
}
