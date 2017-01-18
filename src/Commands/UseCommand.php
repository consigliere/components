<?php

namespace Consigliere\Components\Commands;

use Illuminate\Console\Command as ComponentCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

class UseCommand extends ComponentCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'component:use';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Use the specified component.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $component = Str::studly($this->argument('component'));

        if (!$this->laravel['components']->has($component)) {
            $this->error("Component [{$component}] does not exists.");

            return;
        }

        $this->laravel['components']->setUsed($component);

        $this->info("Component [{$component}] used successfully.");
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['component', InputArgument::REQUIRED, 'The name of component will be used.'],
        ];
    }
}
