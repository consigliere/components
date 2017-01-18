<?php

namespace Components\Recipe\Providers;

use Illuminate\Support\ServiceProvider;

class RecipeServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerBindings();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }

    private function registerBindings()
    {
        $this->app->bind(
            'Components\Recipe\Repositories\RecipeRepository',
            function () {
                $repository = new \Components\Recipe\Repositories\Eloquent\EloquentRecipeRepository(new \Components\Recipe\Entities\Recipe());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Components\Recipe\Repositories\Cache\CacheRecipeDecorator($repository);
            }
        );
// add bindings
    }
}
