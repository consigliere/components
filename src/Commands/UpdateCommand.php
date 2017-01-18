<?php

namespace Consigliere\Components\Commands;

use Illuminate\Console\Command as ComponentCommand;
use Consigliere\Components\Traits\ComponentCommandTrait;
use Symfony\Component\Console\Input\InputArgument;

class UpdateCommand extends ComponentCommand
{
    use ComponentCommandTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'component:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update dependencies for the specified component or for all components.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $this->laravel['components']->update($name = $this->getComponentName());

        $this->info("Component [{$name}] updated successfully.");
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['component', InputArgument::OPTIONAL, 'The name of component will be updated.'],
        ];
    }
}
