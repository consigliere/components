<?php

namespace Consigliere\Components\Commands;

use Illuminate\Console\Command as ComponentCommand;
use Symfony\Component\Console\Input\InputArgument;

class DumpCommand extends ComponentCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'component:dump';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dump-autoload the specified component or for all component.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $this->info('Generating optimized autoload components.');

        if ($component = $this->argument('component')) {
            $this->dump($component);
        } else {
            foreach ($this->laravel['components']->all() as $component) {
                $this->dump($component->getStudlyName());
            }
        }
    }

    public function dump($component)
    {
        $component = $this->laravel['components']->findOrFail($component);

        $this->line("<comment>Running for component</comment>: {$component}");

        chdir($component->getPath());

        passthru('composer dump -o -n -q');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['component', InputArgument::OPTIONAL, 'Component name.'],
        ];
    }
}
