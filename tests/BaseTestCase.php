<?php

namespace Consigliere\Components\tests;

use Consigliere\Components\LaravelComponentsServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class BaseTestCase extends OrchestraTestCase
{
    public function setUp()
    {
        parent::setUp();

        // $this->setUpDatabase();
    }

    private function resetDatabase()
    {
        $this->artisan('migrate:reset', [
            '--database' => 'sqlite',
        ]);
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelComponentsServiceProvider::class,
        ];
    }

    /**
     * Set up the environment.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', array(
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ));
        $app['config']->set('components.paths.components', base_path('components'));
        $app['config']->set('components.paths', [
            'components' => base_path('components'),
            'assets' => public_path('components'),
            'migration' => base_path('database/migrations'),
            'generator' => [
                'assets' => 'Resources/assets',
                'config' => 'Config',
                'command' => 'Console',
                'event' => 'Events',
                'listener' => 'Events/Handlers',
                'migration' => 'Database/Migrations',
                'model' => 'Models',
                'repository' => 'Repositories',
                'seed' => 'Database/Seeds',
                'controller' => 'Http/Controllers',
                'middleware' => 'Http/Middleware',
                'request' => 'Http/Requests',
                'provider' => 'Providers',
                'lang' => 'Resources/lang',
                'views' => 'Resources/views',
                'test' => 'Tests',
                'jobs' => 'Jobs',
                'emails' => 'Emails',
                'notifications' => 'Notifications'
            ],
        ]);
    }

    protected function setUpDatabase()
    {
        $this->resetDatabase();
    }
}
