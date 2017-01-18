<?php

namespace Consigliere\Components\Commands;

use Consigliere\Components\Support\Stub;
use Consigliere\Components\Traits\ComponentCommandTrait;
use Symfony\Component\Console\Input\InputArgument;

class GenerateRouteProviderCommand extends Command
{
    use ComponentCommandTrait;

    protected $argumentName = 'component';
    /**
     * The command name.
     *
     * @var string
     */
    protected $name = 'component:route-provider';

    /**
     * The command description.
     *
     * @var string
     */
    protected $description = 'Generate a new route service provider for the specified component.';

    /**
     * The command arguments.
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
     * Get template contents.
     *
     * @return string
     */
    protected function getTemplateContents()
    {
        $component = $this->laravel['components']->findOrFail($this->getComponentName());

        return (new Stub('/route-provider.stub', [
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
     * Get the destination file path.
     *
     * @return string
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
        return 'RouteServiceProvider';
    }
}
