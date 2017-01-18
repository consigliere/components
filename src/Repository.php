<?php

namespace Consigliere\Components;

use Countable;
use Illuminate\Foundation\Application;
use Illuminate\Support\Str;
use Consigliere\Components\Contracts\RepositoryInterface;
use Consigliere\Components\Exceptions\ComponentNotFoundException;
use Consigliere\Components\Process\Installer;
use Consigliere\Components\Process\Updater;

class Repository implements RepositoryInterface, Countable
{
    /**
     * Application instance.
     *
     * @var Application
     */
    protected $app;

    /**
     * The component path.
     *
     * @var string|null
     */
    protected $path;

    /**
     * The scanned paths.
     *
     * @var array
     */
    protected $paths = [];

    /**
     * @var string
     */
    protected $stubPath;

    /**
     * The constructor.
     *
     * @param Application $app
     * @param string|null $path
     */
    public function __construct(Application $app, $path = null)
    {
        $this->app  = $app;
        $this->path = $path;
    }

    /**
     * Add other component location.
     *
     * @param string $path
     *
     * @return $this
     */
    public function addLocation($path)
    {
        $this->paths[] = $path;

        return $this;
    }

    /**
     * Alternative method for "addPath".
     *
     * @param string $path
     *
     * @return $this
     */
    public function addPath($path)
    {
        return $this->addLocation($path);
    }

    /**
     * Get all additional paths.
     *
     * @return array
     */
    public function getPaths()
    {
        return $this->paths;
    }

    /**
     * Get scanned components paths.
     *
     * @return array
     */
    public function getScanPaths()
    {
        $paths = $this->paths;

        $paths[] = $this->getPath() . '/*';

        if ($this->config('scan.enabled')) {
            $paths = array_merge($paths, $this->config('scan.paths'));
        }

        return $paths;
    }

    /**
     * Get & scan all components.
     *
     * @return array
     */
    public function scan()
    {
        $paths = $this->getScanPaths();

        $components = [];

        foreach ($paths as $key => $path) {
            $manifests = $this->app['files']->glob("{$path}/component.json");

            is_array($manifests) || $manifests = [];

            foreach ($manifests as $manifest) {
                $name = Json::make($manifest)->get('name');

                $components[$name] = new Component($this->app, $name, dirname($manifest));
            }
        }

        return $components;
    }

    /**
     * Get all components.
     *
     * @return array
     */
    public function all()
    {
        if (!$this->config('cache.enabled')) {
            return $this->scan();
        }

        return $this->formatCached($this->getCached());
    }

    /**
     * Format the cached data as array of components.
     *
     * @param array $cached
     *
     * @return array
     */
    protected function formatCached($cached)
    {
        $components = [];

        foreach ($cached as $name => $component) {
            $path = $this->config('paths.components') . '/' . $name;

            $components[$name] = new Component($this->app, $name, $path);
        }

        return $components;
    }

    /**
     * Get cached components.
     *
     * @return array
     */
    public function getCached()
    {
        return $this->app['cache']->remember($this->config('cache.key'), $this->config('cache.lifetime'), function () {
            return $this->toCollection()->toArray();
        });
    }

    /**
     * Get all components as collection instance.
     *
     * @return Collection
     */
    public function toCollection()
    {
        return new Collection($this->scan());
    }

    /**
     * Get components by status.
     *
     * @param $status
     *
     * @return array
     */
    public function getByStatus($status)
    {
        $components = [];

        foreach ($this->all() as $name => $component) {
            if ($component->isStatus($status)) {
                $components[$name] = $component;
            }
        }

        return $components;
    }

    /**
     * Determine whether the given component exist.
     *
     * @param $name
     *
     * @return bool
     */
    public function has($name)
    {
        return array_key_exists($name, $this->all());
    }

    /**
     * Get list of enabled components.
     *
     * @return array
     */
    public function enabled()
    {
        return $this->getByStatus(1);
    }

    /**
     * Get list of disabled components.
     *
     * @return array
     */
    public function disabled()
    {
        return $this->getByStatus(0);
    }

    /**
     * Get count from all components.
     *
     * @return int
     */
    public function count()
    {
        return count($this->all());
    }

    /**
     * Get all ordered components.
     *
     * @param string $direction
     *
     * @return array
     */
    public function getOrdered($direction = 'asc')
    {
        $components = $this->enabled();

        uasort($components, function (Component $a, Component $b) use ($direction) {
            if ($a->order == $b->order) {
                return 0;
            }

            if ($direction == 'desc') {
                return $a->order < $b->order ? 1 : -1;
            }

            return $a->order > $b->order ? 1 : -1;
        });

        return $components;
    }

    /**
     * Get a component path.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path ?: $this->config('paths.components');
    }

    /**
     * Register the components.
     */
    public function register()
    {
        foreach ($this->getOrdered() as $component) {
            $component->register();
        }
    }

    /**
     * Boot the components.
     */
    public function boot()
    {
        foreach ($this->getOrdered() as $component) {
            $component->boot();
        }
    }

    /**
     * Find a specific component.
     *
     * @param $name
     *
     * @return mixed|void
     */
    public function find($name)
    {
        foreach ($this->all() as $component) {
            if ($component->getLowerName() === strtolower($name)) {
                return $component;
            }
        }

        return;
    }

    /**
     * Alternative for "find" method.
     *
     * @param $name
     *
     * @return mixed|void
     */
    public function get($name)
    {
        return $this->find($name);
    }

    /**
     * Find a specific component, if there return that, otherwise throw exception.
     *
     * @param $name
     *
     * @return Component
     *
     * @throws ComponentNotFoundException
     */
    public function findOrFail($name)
    {
        $component = $this->find($name);

        if ($component !== null) {
            return $component;
        }

        throw new ComponentNotFoundException("Component [{$name}] does not exist!");
    }

    /**
     * Get all components as laravel collection instance.
     *
     * @return Collection
     */
    public function collections()
    {
        return new Collection($this->enabled());
    }

    /**
     * Get component path for a specific component.
     *
     * @param $component
     *
     * @return string
     */
    public function getComponentPath($component)
    {
        try {
            return $this->findOrFail($component)->getPath() . '/';
        } catch (ComponentNotFoundException $e) {
            return $this->getPath() . '/' . Str::studly($component) . '/';
        }
    }

    /**
     * Get asset path for a specific component.
     *
     * @param $component
     *
     * @return string
     */
    public function assetPath($component)
    {
        return $this->config('paths.assets') . '/' . $component;
    }

    /**
     * Get a specific config data from a configuration file.
     *
     * @param      $key
     *
     * @param null $default
     *
     * @return mixed
     */
    public function config($key, $default = null)
    {
        return $this->app['config']->get('components.' . $key, $default);
    }

    /**
     * Get storage path for component used.
     *
     * @return string
     */
    public function getUsedStoragePath()
    {
        if (!$this->app['files']->exists($path = storage_path('app/components'))) {
            $this->app['files']->makeDirectory($path, 0777, true);
        }

        return $path . '/components.used';
    }

    /**
     * Set component used for cli session.
     *
     * @param $name
     *
     * @throws ComponentNotFoundException
     */
    public function setUsed($name)
    {
        $component = $this->findOrFail($name);

        $this->app['files']->put($this->getUsedStoragePath(), $component);
    }

    /**
     * Get component used for cli session.
     *
     * @return string
     */
    public function getUsedNow()
    {
        return $this->findOrFail($this->app['files']->get($this->getUsedStoragePath()));
    }

    /**
     * Get used now.
     *
     * @return string
     */
    public function getUsed()
    {
        return $this->getUsedNow();
    }

    /**
     * Get laravel filesystem instance.
     *
     * @return \Illuminate\Filesystem\Filesystem
     */
    public function getFiles()
    {
        return $this->app['files'];
    }

    /**
     * Get component assets path.
     *
     * @return string
     */
    public function getAssetsPath()
    {
        return $this->config('paths.assets');
    }

    /**
     * Get asset url from a specific component.
     *
     * @param string $asset
     *
     * @return string
     */
    public function asset($asset)
    {
        list($name, $url) = explode('::', $asset);

        $baseUrl = str_replace(public_path() . DIRECTORY_SEPARATOR, '', $this->getAssetsPath());

        $url = $this->app['url']->asset($baseUrl . "/{$name}/" . $url);

        return str_replace(['http://', 'https://'], '//', $url);
    }

    /**
     * Determine whether the given component is activated.
     *
     * @param string $name
     *
     * @return bool
     */
    public function active($name)
    {
        return $this->findOrFail($name)->active();
    }

    /**
     * Determine whether the given component is not activated.
     *
     * @param string $name
     *
     * @return bool
     */
    public function notActive($name)
    {
        return !$this->active($name);
    }

    /**
     * Enabling a specific component.
     *
     * @param string $name
     *
     * @return bool
     */
    public function enable($name)
    {
        return $this->findOrFail($name)->enable();
    }

    /**
     * Disabling a specific component.
     *
     * @param string $name
     *
     * @return bool
     */
    public function disable($name)
    {
        return $this->findOrFail($name)->disable();
    }

    /**
     * Delete a specific component.
     *
     * @param string $name
     *
     * @return bool
     */
    public function delete($name)
    {
        return $this->findOrFail($name)->delete();
    }

    /**
     * Update dependencies for the specified component.
     *
     * @param string $component
     */
    public function update($component)
    {
        with(new Updater($this))->update($component);
    }

    /**
     * Install the specified component.
     *
     * @param string $name
     * @param string $version
     * @param string $type
     * @param bool   $subtree
     *
     * @return \Symfony\Component\Process\Process
     */
    public function install($name, $version = 'dev-master', $type = 'composer', $subtree = false)
    {
        $installer = new Installer($name, $version, $type, $subtree);

        return $installer->run();
    }

    /**
     * Get stub path.
     *
     * @return string
     */
    public function getStubPath()
    {
        if ($this->stubPath !== null) {
            return $this->stubPath;
        }

        if ($this->config('stubs.enabled') === true) {
            return $this->config('stubs.path');
        }

        return $this->stubPath;
    }

    /**
     * Set stub path.
     *
     * @param string $stubPath
     *
     * @return $this
     */
    public function setStubPath($stubPath)
    {
        $this->stubPath = $stubPath;

        return $this;
    }
}
