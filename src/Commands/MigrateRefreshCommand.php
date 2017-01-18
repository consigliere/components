<?php

namespace Consigliere\Components\Commands;

use Illuminate\Console\Command as ComponentCommand;
use Consigliere\Components\Traits\ComponentCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MigrateRefreshCommand extends ComponentCommand
{
    use ComponentCommandTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'component:migrate-refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rollback & re-migrate the components migrations.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $this->call('component:migrate-reset', [
            'component'     => $this->getComponentName(),
            '--database' => $this->option('database'),
            '--force'    => $this->option('force'),
        ]);

        $this->call('component:migrate', [
            'component'     => $this->getComponentName(),
            '--database' => $this->option('database'),
            '--force'    => $this->option('force'),
        ]);

        if ($this->option('seed')) {
            $this->call('component:seed', [
                'component' => $this->getComponentName(),
            ]);
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
            ['database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use.'],
            ['force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production.'],
            ['seed', null, InputOption::VALUE_NONE, 'Indicates if the seed task should be re-run.'],
        ];
    }
}
