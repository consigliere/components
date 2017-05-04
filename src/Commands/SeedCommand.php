<?php

namespace Consigliere\Components\Commands;

use Illuminate\Console\Command as ComponentCommand;
use Illuminate\Support\Str;
use RuntimeException;
use Consigliere\Components\Component;
use Consigliere\Components\Repository;
use Consigliere\Components\Traits\ComponentCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class SeedCommand extends ComponentCommand
{
    use ComponentCommandTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'component:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run database seeder from the specified component or from all components.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        try {
            if ($name = $this->argument('component')) {
                $name = Str::studly($name);
                $this->componentSeed($this->getComponentByName($name));
            } else {
                $components = $this->getComponentRepository()->getOrdered();
                array_walk($components, [$this, 'componentSeed']);
                $this->info('All components seeded.');
            }
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * @throws RuntimeException
     *
     * @return Repository
     */
    public function getComponentRepository()
    {
        $components = $this->laravel['components'];
        if (!$components instanceof Repository) {
            throw new RuntimeException("Component repository not found!");
        }

        return $components;
    }

    /**
     * @param $name
     *
     * @throws RuntimeException
     *
     * @return Component
     */
    public function getComponentByName($name)
    {
        $components = $this->getComponentRepository();
        if ($components->has($name) === false) {
            throw new RuntimeException("Component [$name] does not exists.");
        }

        return $components->get($name);
    }

    /**
     * @param Component $component
     *
     * @return void
     */
    public function componentSeed(Component $component)
    {
        $seeders = [];
        $name    = $component->getName();
        $config  = $component->get('seed');
        if (is_array($config) && array_key_exists('seeds', $config)) {
            foreach ((array)$config['seeds'] as $class) {
                if (@class_exists($class)) {
                    $seeders[] = $class;
                }
            }
        } else {
            $class = $this->getSeederName($name); //legacy support
            if (@class_exists($class)) {
                $seeders[] = $class;
            }
        }

        if (count($seeders) > 0) {
            array_walk($seeders, [$this, 'dbSeed']);
            $this->info("Component [$name] seeded.");
        }
    }

    /**
     * Seed the specified component.
     *
     * @param string $className
     *
     * @return array
     */
    protected function dbSeed($className)
    {
        $params = [
            '--class' => $className,
        ];

        if ($option = $this->option('database')) {
            $params['--database'] = $option;
        }

        if ($option = $this->option('force')) {
            $params['--force'] = $option;
        }

        $this->call('db:seed', $params);
    }

    /**
     * Get master database seeder name for the specified component.
     *
     * @param string $name
     *
     * @return string
     */
    public function getSeederName($name)
    {
        $name = Str::studly($name);

        $namespace = $this->laravel['components']->config('namespace');

        return $namespace . '\\' . $name . '\Database\Seeders\\' . $name . 'DatabaseSeeder';
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
            ['database', null, InputOption::VALUE_OPTIONAL, 'The database connection to seed.'],
            ['force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production.'],
        ];
    }
}
