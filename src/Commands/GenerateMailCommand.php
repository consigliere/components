<?php

namespace Consigliere\Components\Commands;

use Consigliere\Components\Support\Stub;
use Consigliere\Components\Traits\ComponentCommandTrait;
use Symfony\Component\Console\Input\InputArgument;

class GenerateMailCommand extends Command
{
    use ComponentCommandTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'component:make-mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new Mailable Class for the specified component';

    protected $argumentName = 'name';

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the mailable.'],
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

        return (new Stub('/mail.stub', [
            'NAMESPACE' => $this->getClassNamespace($component),
            'CLASS'     => $this->getClass(),
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

        $mailPath = $this->laravel['components']->config('paths.generator.emails', 'Emails');

        return $path . $mailPath . '/' . $this->getFileName() . '.php';
    }

    /**
     * @return string
     */
    private function getFileName()
    {
        return studly_case($this->argument('name'));
    }

    /**
     * @return string
     */
    public function getDefaultNamespace()
    {
        return $this->laravel['components']->config('paths.generator.emails', 'Emails');
    }
}
