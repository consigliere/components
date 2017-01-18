<?php

namespace Consigliere\Components\Commands;

use Illuminate\Console\Command as ComponentCommand;
use Symfony\Component\Console\Input\InputArgument;

class EnableCommand extends ComponentCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'component:enable';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enable the specified component.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $component = $this->laravel['components']->findOrFail($this->argument('component'));

        if ($component->disabled()) {
            $component->enable();

            $this->info("Component [{$component}] enabled successful.");
        } else {
            $this->comment("Component [{$component}] has already enabled.");
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['component', InputArgument::REQUIRED, 'Component name.'],
        ];
    }
}
