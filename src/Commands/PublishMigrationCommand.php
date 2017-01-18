<?php

namespace Consigliere\Components\Commands;

use Illuminate\Console\Command as ComponentCommand;
use Consigliere\Components\Migrations\Migrator;
use Consigliere\Components\Publishing\MigrationPublisher;
use Symfony\Component\Console\Input\InputArgument;

class PublishMigrationCommand extends ComponentCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'component:publish-migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Publish a component's migrations to the application";

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        if ($name = $this->argument('component')) {
            $component = $this->laravel['components']->findOrFail($name);

            $this->publish($component);

            return;
        }

        foreach ($this->laravel['components']->enabled() as $component) {
            $this->publish($component);
        }
    }

    /**
     * Publish migration for the specified component.
     *
     * @param \Consigliere\Components\Component $component
     */
    public function publish($component)
    {
        with(new MigrationPublisher(new Migrator($component)))
            ->setRepository($this->laravel['components'])
            ->setConsole($this)
            ->publish();
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['component', InputArgument::OPTIONAL, 'The name of component being used.'],
        ];
    }
}
