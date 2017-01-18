<?php

namespace Consigliere\Components;

use Consigliere\Components\Providers\BootstrapServiceProvider;
use Consigliere\Components\Providers\ConsoleServiceProvider;
use Consigliere\Components\Providers\ContractsServiceProvider;
use Consigliere\Components\Support\Stub;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Booting the package.
     */
    public function boot()
    {
        $this->registerNamespaces();

        $this->registerComponents();
    }

    /**
     * Register all components.
     */
    protected function registerComponents()
    {
        $this->app->register(BootstrapServiceProvider::class);
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->registerServices();
        $this->setupStubPath();
        $this->registerProviders();
    }

    /**
     * Setup stub path.
     */
    public function setupStubPath()
    {
        $this->app->booted(function ($app) {
            Stub::setBasePath(__DIR__ . '/Commands/stubs');

            if ($app['components']->config('stubs.enabled') === true) {
                Stub::setBasePath($app['components']->config('stubs.path'));
            }
        });
    }

    /**
     * Register package's namespaces.
     */
    protected function registerNamespaces()
    {
        $configPath = __DIR__ . '/../config/config.php';
        $this->mergeConfigFrom($configPath, 'components');
        $this->publishes([$configPath => config_path('components.php')], 'config');
    }

    /**
     * Register the service provider.
     */
    protected function registerServices()
    {
        $this->app->singleton('components', function ($app) {
            $path = $app['config']->get('components.paths.components');

            return new Repository($app, $path);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['components'];
    }

    /**
     * Register providers.
     */
    protected function registerProviders()
    {
        $this->app->register(ConsoleServiceProvider::class);
        $this->app->register(ContractsServiceProvider::class);
    }
}
