<?php

namespace Consigliere\Components\Commands;

use Consigliere\Components\Component;
use Consigliere\Components\Support\Stub;
use Consigliere\Components\Traits\ComponentCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class GenerateListenerCommand extends Command
{
    use ComponentCommandTrait;

    protected $argumentName = 'name';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'component:make-listener';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new Listener Class for the specified component';

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the command.'],
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
            ['event', null, InputOption::VALUE_REQUIRED, 'Event name this is listening to', null],
        ];
    }

    protected function getTemplateContents()
    {
        $component = $this->laravel['components']->findOrFail($this->getComponentName());

        return (new Stub('/listener.stub', [
            'NAMESPACE'          => $this->getNamespace($component),
            "EVENTNAME"          => $this->getEventName($component),
            "EVENTSHORTENEDNAME" => $this->option('event'),
            "CLASS"              => $this->getClass(),
            'DUMMYNAMESPACE'     => $this->laravel->getNamespace() . "Events",
        ]))->render();
    }

    protected function getDestinationFilePath()
    {
        $path = $this->laravel['components']->getComponentPath($this->getComponentName());

        $seederPath = $this->laravel['components']->config('paths.generator.listener');

        return $path . $seederPath . '/' . $this->getFileName() . '.php';
    }

    /**
     * @return string
     */
    protected function getFileName()
    {
        return studly_case($this->argument('name'));
    }

    public function fire()
    {
        if (!$this->option('event')) {
            return $this->error('The --event option is necessary');
        }

        parent::fire();
    }

    protected function getEventName(Component $component)
    {
        return $this->getClassNamespace($component) . "\\" . config('components.paths.generator.event') . "\\" . $this->option('event');
    }

    private function getNamespace($component)
    {
        $namespace = str_replace('/', '\\', config('components.paths.generator.listener'));

        return $this->getClassNamespace($component) . "\\" . $namespace;
    }
}
