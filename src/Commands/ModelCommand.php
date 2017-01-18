<?php

namespace Consigliere\Components\Commands;

use Illuminate\Support\Str;
use Consigliere\Components\Support\Stub;
use Consigliere\Components\Traits\ComponentCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ModelCommand extends Command
{
    use ComponentCommandTrait;

    /**
     * The name of argument name.
     *
     * @var string
     */
    protected $argumentName = 'model';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'component:make-model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new model for the specified component.';

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['model', InputArgument::REQUIRED, 'The name of model will be created.'],
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
            ['fillable', null, InputOption::VALUE_OPTIONAL, 'The fillable attributes.', null],
        ];
    }

    /**
     * @return mixed
     */
    protected function getTemplateContents()
    {
        $component = $this->laravel['components']->findOrFail($this->getComponentName());

        return (new Stub('/model.stub', [
            'NAME'                => $this->getModelName(),
            'FILLABLE'            => $this->getFillable(),
            'NAMESPACE'           => $this->getClassNamespace($component),
            'CLASS'               => $this->getClass(),
            'LOWER_NAME'          => $component->getLowerName(),
            'COMPONENT'           => $this->getComponentName(),
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

        $seederPath = $this->laravel['components']->config('paths.generator.model');

        return $path . $seederPath . '/' . $this->getModelName() . '.php';
    }

    /**
     * @return mixed|string
     */
    private function getModelName()
    {
        return Str::studly($this->argument('model'));
    }

    /**
     * @return string
     */
    private function getFillable()
    {
        $fillable = $this->option('fillable');

        if (!is_null($fillable)) {
            $arrays = explode(',', $fillable);

            return json_encode($arrays);
        }

        return '[]';
    }

    /**
     * Get default namespace.
     *
     * @return string
     */
    public function getDefaultNamespace()
    {
        return $this->laravel['components']->config('paths.generator.model');
    }
}
