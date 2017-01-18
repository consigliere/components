<?php

namespace Consigliere\Components\Commands;

use Illuminate\Support\Str;
use Consigliere\Components\Support\Stub;
use Consigliere\Components\Traits\ComponentCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class GenerateProviderCommand extends Command
{
    use ComponentCommandTrait;

    /**
     * The name of argument name.
     *
     * @var string
     */
    protected $argumentName = 'name';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'component:make-provider';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new service provider for the specified component.';

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The service provider name.'],
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
            ['master', null, InputOption::VALUE_NONE, 'Indicates the master service provider', null],
        ];
    }

    /**
     * @return mixed
     */
    protected function getTemplateContents()
    {
        $stub = $this->option('master') ? 'scaffold/provider' : 'provider';

        $component = $this->laravel['components']->findOrFail($this->getComponentName());

        return (new Stub('/' . $stub . '.stub', [
            'NAMESPACE'           => $this->getClassNamespace($component),
            'CLASS'               => $this->getClass(),
            'LOWER_NAME'          => $component->getLowerName(),
            'COMPONENT'           => $this->getComponentName(),
            'NAME'                => $this->getFileName(),
            'STUDLY_NAME'         => $component->getStudlyName(),
            'COMPONENT_NAMESPACE' => $this->laravel['components']->config('namespace'),
            'PATH_VIEWS'          => $this->laravel['config']->get('components.paths.generator.views'),
            'PATH_LANG'           => $this->laravel['config']->get('components.paths.generator.lang'),
            'PATH_CONFIG'         => $this->laravel['config']->get('components.paths.generator.config'),
        ]))->render();
    }

    /**
     * @return mixed
     */
    protected function getDestinationFilePath()
    {
        $path = $this->laravel['components']->getComponentPath($this->getComponentName());

        $generatorPath = $this->laravel['components']->config('paths.generator.provider');

        return $path . $generatorPath . '/' . $this->getFileName() . '.php';
    }

    /**
     * @return string
     */
    private function getFileName()
    {
        return Str::studly($this->argument('name'));
    }

    /**
     * Get default namespace.
     *
     * @return string
     */
    public function getDefaultNamespace()
    {
        return 'Providers';
    }
}
