<?php

namespace Consigliere\Components\Commands;

use Consigliere\Components\Support\Stub;
use Consigliere\Components\Traits\ComponentCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class GenerateEventCommand extends Command
{
    use ComponentCommandTrait;

    protected $argumentName = 'name';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'component:make-event';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new Event Class for the specified component';

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the event.'],
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
            ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }

    public function getTemplateContents()
    {
        $component = $this->laravel['components']->findOrFail($this->getComponentName());

        return (new Stub('/event.stub', [
            'NAMESPACE'      => $this->getClassNamespace($component) . "\\" . config('components.paths.generator.event'),
            "CLASS"          => $this->getClass(),
            'DUMMYNAMESPACE' => $this->laravel->getNamespace() . 'Events',
        ]))->render();
    }

    public function getDestinationFilePath()
    {
        $path       = $this->laravel['components']->getComponentPath($this->getComponentName());
        $seederPath = $this->laravel['components']->config('paths.generator.event');

        return $path . $seederPath . '/' . $this->getFileName() . '.php';
    }

    /**
     * @return string
     */
    protected function getFileName()
    {
        return studly_case($this->argument('name'));
    }
}
