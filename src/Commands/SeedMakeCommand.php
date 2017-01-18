<?php

namespace Consigliere\Components\Commands;

use Illuminate\Support\Str;
use Consigliere\Components\Support\Stub;
use Consigliere\Components\Traits\CanClearComponentsCache;
use Consigliere\Components\Traits\ComponentCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class SeedMakeCommand extends Command
{
    use ComponentCommandTrait, CanClearComponentsCache;

    protected $argumentName = 'name';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'component:make-seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new seeder for the specified component.';

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of seeder will be created.'],
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
            [
                'master',
                null,
                InputOption::VALUE_NONE,
                'Indicates the seeder will created is a master database seeder.',
            ],
        ];
    }

    /**
     * @return mixed
     */
    protected function getTemplateContents()
    {
        $component = $this->laravel['components']->findOrFail($this->getComponentName());

        return (new Stub('/seeder.stub', [
            'NAME'      => $this->getSeederName(),
            'COMPONENT' => $this->getComponentName(),
            'NAMESPACE' => $this->getClassNamespace($component),
        ]))->render();
    }

    /**
     * @return mixed
     */
    protected function getDestinationFilePath()
    {
        $this->clearCache();

        $path = $this->laravel['components']->getComponentPath($this->getComponentName());

        $seederPath = $this->laravel['components']->config('paths.generator.seed');

        return $path . $seederPath . '/' . $this->getSeederName() . '.php';
    }

    /**
     * Get seeder name.
     *
     * @return string
     */
    private function getSeederName()
    {
        $end = $this->option('master') ? 'DatabaseSeeder' : 'TableSeeder';

        return Str::studly($this->argument('name')) . $end;
    }
}
