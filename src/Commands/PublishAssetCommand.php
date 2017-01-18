<?php

namespace Consigliere\Components\Commands;

use Illuminate\Console\Command as ComponentCommand;
use Consigliere\Components\Component;
use Consigliere\Components\Publishing\AssetPublisher;
use Symfony\Component\Console\Input\InputArgument;

class PublishAssetCommand extends ComponentCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'component:publish-asset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish a component\'s assets to the application';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        if ($name = $this->argument('component')) {
            return $this->publish($name);
        }

        $this->publishAll();
    }

    /**
     * Publish assets from all components.
     */
    public function publishAll()
    {
        foreach ($this->laravel['components']->enabled() as $component) {
            $this->publish($component);
        }
    }

    /**
     * Publish assets from the specified component.
     *
     * @param string $name
     */
    public function publish($name)
    {
        if ($name instanceof Component) {
            $component = $name;
        } else {
            $component = $this->laravel['components']->findOrFail($name);
        }

        with(new AssetPublisher($component))
            ->setRepository($this->laravel['components'])
            ->setConsole($this)
            ->publish();

        $this->line("<info>Published</info>: {$component->getStudlyName()}");
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['component', InputArgument::OPTIONAL, 'The name of component will be used.'],
        ];
    }
}
