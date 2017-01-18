<?php

namespace Consigliere\Components\Commands;

use Illuminate\Console\Command as ComponentCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class PublishConfigurationCommand extends ComponentCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'component:publish-config';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish a component\'s config files to the application';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        if ($component = $this->argument('component')) {
            $this->publishConfiguration($component);

            return;
        }

        foreach ($this->laravel['components']->enabled() as $component) {
            $this->publishConfiguration($component->getName());
        }
    }

    /**
     * @param string $component
     *
     * @return string
     */
    private function getServiceProviderForComponent($component)
    {
        $studlyName = studly_case($component);

        return "Components\\$studlyName\\Providers\\{$studlyName}ServiceProvider";
    }

    /**
     * @param string $component
     */
    private function publishConfiguration($component)
    {
        $this->call('vendor:publish', [
            '--provider' => $this->getServiceProviderForComponent($component),
            '--force'    => $this->option('force'),
            '--tag'      => ['config'],
        ]);
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

    /**
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['--force', '-f', InputOption::VALUE_NONE, 'Force the publishing of config files'],
        ];
    }
}
