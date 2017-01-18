<?php

namespace Consigliere\Components\Providers;

use Illuminate\Support\ServiceProvider;
use Consigliere\Components\Contracts\RepositoryInterface;
use Consigliere\Components\Repository;

class ContractsServiceProvider extends ServiceProvider
{
    /**
     * Register some binding.
     */
    public function register()
    {
        $this->app->bind(RepositoryInterface::class, Repository::class);
    }
}
