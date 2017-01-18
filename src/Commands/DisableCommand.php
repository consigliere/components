<?php

namespace Consigliere\Components\Commands;

use Illuminate\Console\Command as ComponentCommand;
use Symfony\Component\Console\Input\InputArgument;

class DisableCommand extends ComponentCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'component:disable';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Disable the specified component.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $component = $this->laravel['components']->findOrFail($this->argument('component'));

        if ($component->enabled()) {
            $component->disable();

            $this->info("Component [{$component}] disabled successful.");
        } else {
            $this->comment("Component [{$component}] has already disabled.");
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
