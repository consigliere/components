<?php

namespace Consigliere\Components\Commands;

use Illuminate\Console\Command as ComponentCommand;
use Symfony\Component\Console\Input\InputOption;

class ListCommand extends ComponentCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'component:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show list of all components.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $this->table(['Name', 'Status', 'Order', 'Path'], $this->getRows());
    }

    /**
     * Get table rows.
     *
     * @return array
     */
    public function getRows()
    {
        $rows = [];

        foreach ($this->getComponents() as $component) {
            $rows[] = [
                $component->getStudlyName(),
                $component->enabled() ? 'Enabled' : 'Disabled',
                $component->get('order'),
                $component->getPath(),
            ];
        }

        return $rows;
    }

    public function getComponents()
    {
        switch ($this->option('only')) {
            case 'enabled':
                return $this->laravel['components']->getByStatus(1);
                break;

            case 'disabled':
                return $this->laravel['components']->getByStatus(0);
                break;

            case 'ordered':
                return $this->laravel['components']->getOrdered($this->option('direction'));
                break;

            default:
                return $this->laravel['components']->all();
                break;
        }
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['only', null, InputOption::VALUE_OPTIONAL, 'Types of components will be displayed.', null],
            ['direction', 'd', InputOption::VALUE_OPTIONAL, 'The direction of ordering.', 'asc'],
        ];
    }
}
