<?php

namespace Consigliere\Components\Traits;

trait MigrationLoaderTrait
{
    /**
     * Include all migrations files from the specified component.
     *
     * @param string $component
     */
    protected function loadMigrationFiles($component)
    {
        $path = $this->laravel['components']->getComponentPath($component) . $this->getMigrationGeneratorPath();

        $files = $this->laravel['files']->glob($path . '/*_*.php');

        foreach ($files as $file) {
            $this->laravel['files']->requireOnce($file);
        }
    }

    /**
     * Get migration generator path.
     *
     * @return string
     */
    protected function getMigrationGeneratorPath()
    {
        return $this->laravel['components']->config('paths.generator.migration');
    }
}
