<?php

namespace Consigliere\Components\Commands;

use Illuminate\Support\Str;
use Consigliere\Components\Support\Stub;
use Consigliere\Components\Traits\ComponentCommandTrait;
use Symfony\Component\Console\Input\InputArgument;

class MakeRequestCommand extends Command
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
    protected $name = 'component:make-request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new form request class for the specified component.';

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the form request class.'],
            ['component', InputArgument::OPTIONAL, 'The name of component will be used.'],
        ];
    }

    /**
     * @return mixed
     */
    protected function getTemplateContents()
    {
        $component = $this->laravel['components']->findOrFail($this->getComponentName());

        return (new Stub('/request.stub', [
            'NAMESPACE'           => $this->getClassNamespace($component),
            'CLASS'               => $this->getClass(),
            'LOWER_NAME'          => $component->getLowerName(),
            'COMPONENT'           => $this->getComponentName(),
            'NAME'                => $this->getFileName(),
            'STUDLY_NAME'         => $component->getStudlyName(),
            'COMPONENT_NAMESPACE' => $this->laravel['components']->config('namespace'),
        ]))->render();
    }

    /**
     * @return mixed
     */
    protected function getDestinationFilePath()
    {
        $path = $this->laravel['components']->getComponentPath($this->getComponentName());

        $seederPath = $this->laravel['components']->config('paths.generator.request');

        return $path . $seederPath . '/' . $this->getFileName() . '.php';
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
        return 'Http\Requests';
    }
}
