<?php

namespace Consigliere\Components\Commands;

use Illuminate\Console\Command as ComponentCommand;
use Consigliere\Components\Generators\ComponentGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MakeCommand extends ComponentCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'component:make';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new component.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $names = $this->argument('name');

        foreach ($names as $name) {
            with(new ComponentGenerator($name))
                ->setFilesystem($this->laravel['files'])
                ->setComponent($this->laravel['components'])
                ->setConfig($this->laravel['config'])
                ->setConsole($this)
                ->setForce($this->option('force'))
                ->setPlain($this->option('plain'))
                ->generate();
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
            ['name', InputArgument::IS_ARRAY, 'The names of components will be created.'],
        ];
    }

    protected function getOptions()
    {
        return [
            ['plain', 'p', InputOption::VALUE_NONE, 'Generate a plain component (without some resources).'],
            ['force', null, InputOption::VALUE_NONE, 'Force the operation to run when component already exist.'],
        ];
    }
}
