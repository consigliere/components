<?php

namespace Consigliere\Components\Commands;

use Illuminate\Console\Command as ComponentCommand;
use Consigliere\Components\Migrations\Migrator;
use Consigliere\Components\Component;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MigrateCommand extends ComponentCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'component:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate the migrations from the specified component or from all components.';

    /**
     * @var \Consigliere\Components\Repository
     */
    protected $component;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $this->component = $this->laravel['components'];

        $name = $this->argument('component');

        if ($name) {
            $component = $this->component->findOrFail($name);
            return $this->migrate($component);
        }

        foreach ($this->component->getOrdered($this->option('direction')) as $component) {
            $this->line('Running for component: <info>' . $component->getName() . '</info>');

            $this->migrate($component);
        }
    }

    /**
     * Run the migration from the specified component.
     *
     * @param Component $component
     *
     * @return mixed
     */
    protected function migrate(Component $component)
    {
        $path = str_replace(base_path(), '', (new Migrator($component))->getPath());
        $this->call('migrate', [
            '--path'     => $path,
            '--database' => $this->option('database'),
            '--pretend'  => $this->option('pretend'),
            '--force'    => $this->option('force'),
        ]);

        if ($this->option('seed')) {
            $this->call('component:seed', ['component' => $component->getName()]);
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
            ['component', InputArgument::OPTIONAL, 'The name of component will be used.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['direction', 'd', InputOption::VALUE_OPTIONAL, 'The direction of ordering.', 'asc'],
            ['database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use.'],
            ['pretend', null, InputOption::VALUE_NONE, 'Dump the SQL queries that would be run.'],
            ['force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production.'],
            ['seed', null, InputOption::VALUE_NONE, 'Indicates if the seed task should be re-run.'],
        ];
    }
}
