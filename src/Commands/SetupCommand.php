<?php

namespace Consigliere\Components\Commands;

use Illuminate\Console\Command as ComponentCommand;

class SetupCommand extends ComponentCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'component:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setting up components folders for first use.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $this->generateComponentsFolder();

        $this->generateAssetsFolder();
    }

    /**
     * Generate the components folder.
     */
    public function generateComponentsFolder()
    {
        $this->generateDirectory($this->laravel['components']->config('paths.components'),
            'Components directory created successfully',
            'Components directory already exist'
        );
    }

    /**
     * Generate the assets folder.
     */
    public function generateAssetsFolder()
    {
        $this->generateDirectory($this->laravel['components']->config('paths.assets'),
            'Assets directory created successfully',
            'Assets directory already exist'
        );
    }

    /**
     * Generate the specified directory by given $dir.
     *
     * @param $dir
     * @param $success
     * @param $error
     */
    protected function generateDirectory($dir, $success, $error)
    {
        if (!$this->laravel['files']->isDirectory($dir)) {
            $this->laravel['files']->makeDirectory($dir);

            $this->info($success);

            return;
        }

        $this->error($error);
    }
}
