<?php

namespace Consigliere\Components\Providers;

use Illuminate\Support\ServiceProvider;
use Consigliere\Components\Commands\MakeCommandCommand;
use Consigliere\Components\Commands\MakeControllerCommand;
use Consigliere\Components\Commands\DisableCommand;
use Consigliere\Components\Commands\DumpCommand;
use Consigliere\Components\Commands\EnableCommand;
use Consigliere\Components\Commands\GenerateEventCommand;
use Consigliere\Components\Commands\GenerateJobCommand;
use Consigliere\Components\Commands\GenerateListenerCommand;
use Consigliere\Components\Commands\GenerateMailCommand;
use Consigliere\Components\Commands\GenerateMiddlewareCommand;
use Consigliere\Components\Commands\GenerateNotificationCommand;
use Consigliere\Components\Commands\GenerateProviderCommand;
use Consigliere\Components\Commands\GenerateRouteProviderCommand;
use Consigliere\Components\Commands\InstallCommand;
use Consigliere\Components\Commands\ListCommand;
use Consigliere\Components\Commands\MakeCommand;
use Consigliere\Components\Commands\MakeRequestCommand;
use Consigliere\Components\Commands\MigrateCommand;
use Consigliere\Components\Commands\MigrateRefreshCommand;
use Consigliere\Components\Commands\MigrateResetCommand;
use Consigliere\Components\Commands\MigrateRollbackCommand;
use Consigliere\Components\Commands\MigrationCommand;
use Consigliere\Components\Commands\ModelCommand;
use Consigliere\Components\Commands\PublishAssetCommand;
use Consigliere\Components\Commands\PublishConfigurationCommand;
use Consigliere\Components\Commands\PublishMigrationCommand;
use Consigliere\Components\Commands\PublishTranslationCommand;
use Consigliere\Components\Commands\SeedCommand;
use Consigliere\Components\Commands\SeedMakeCommand;
use Consigliere\Components\Commands\SetupCommand;
use Consigliere\Components\Commands\UpdateCommand;
use Consigliere\Components\Commands\UseCommand;
use Consigliere\Components\Commands\PublishSeedCommand;

class ConsoleServiceProvider extends ServiceProvider
{
    protected $defer = false;

    /**
     * The available commands
     *
     * @var array
     */
    protected $commands
        = [
            MakeCommand::class,
            MakeCommandCommand::class,
            MakeControllerCommand::class,
            DisableCommand::class,
            EnableCommand::class,
            GenerateEventCommand::class,
            GenerateListenerCommand::class,
            GenerateMiddlewareCommand::class,
            GenerateProviderCommand::class,
            GenerateRouteProviderCommand::class,
            InstallCommand::class,
            ListCommand::class,
            MigrateCommand::class,
            MigrateRefreshCommand::class,
            MigrateResetCommand::class,
            MigrateRollbackCommand::class,
            MigrationCommand::class,
            ModelCommand::class,
            PublishAssetCommand::class,
            PublishMigrationCommand::class,
            PublishTranslationCommand::class,
            SeedCommand::class,
            SeedMakeCommand::class,
            SetupCommand::class,
            UpdateCommand::class,
            UseCommand::class,
            DumpCommand::class,
            MakeRequestCommand::class,
            PublishConfigurationCommand::class,
            GenerateJobCommand::class,
            GenerateMailCommand::class,
            GenerateNotificationCommand::class,
            PublishSeedCommand::class
        ];

    /**
     * Register the commands.
     */
    public function register()
    {
        $this->commands($this->commands);
    }

    /**
     * @return array
     */
    public function provides()
    {
        $provides = $this->commands;

        return $provides;
    }
}
