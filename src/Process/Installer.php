<?php

namespace Consigliere\Components\Process;

use Illuminate\Console\Command as ComponentCommand;
use Illuminate\Support\Str;
use Consigliere\Components\Repository;
use Symfony\Component\Process\Process;

class Installer
{
    /**
     * The component name.
     *
     * @var string
     */
    protected $name;

    /**
     * The version of component being installed.
     *
     * @var string
     */
    protected $version;

    /**
     * The component repository instance.
     *
     * @var \Consigliere\Components\Repository
     */
    protected $repository;

    /**
     * The console command instance.
     *
     * @var \Illuminate\Console\Command
     */
    protected $console;

    /**
     * The destionation path.
     *
     * @var string
     */
    protected $path;

    /**
     * The process timeout.
     *
     * @var int
     */
    protected $timeout = 3360;

    /**
     * The constructor.
     *
     * @param string $name
     * @param string $version
     * @param string $type
     * @param bool   $tree
     */
    public function __construct($name, $version = null, $type = null, $tree = false)
    {
        $this->name    = $name;
        $this->version = $version;
        $this->type    = $type;
        $this->tree    = $tree;
    }

    /**
     * Set destination path.
     *
     * @param string $path
     *
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Set the component repository instance.
     *
     * @param \Consigliere\Components\Repository $repository
     *
     * @return $this
     */
    public function setRepository(Repository $repository)
    {
        $this->repository = $repository;

        return $this;
    }

    /**
     * Set console command instance.
     *
     * @param \Consigliere\Components\Process\Command $console
     *
     * @return $this
     */
    public function setConsole(Command $console)
    {
        $this->console = $console;

        return $this;
    }

    /**
     * Set process timeout.
     *
     * @param int $timeout
     *
     * @return $this
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;

        return $this;
    }

    /**
     * Run the installation process.
     *
     * @return \Symfony\Component\Process\Process
     */
    public function run()
    {
        $process = $this->getProcess();

        $process->setTimeout($this->timeout);

        if ($this->console instanceof Command) {
            $process->run(function ($type, $line) {
                $this->console->line($line);
            });
        }

        return $process;
    }

    /**
     * Get process instance.
     *
     * @return \Symfony\Component\Process\Process
     */
    public function getProcess()
    {
        switch ($this->type) {
            case 'github':
            case 'github-https':
            case 'bitbucket':
                if ($this->tree) {
                    $process = $this->installViaSubtree();
                }

                $process = $this->installViaGit();
                break;

            default:
                $process = $this->installViaComposer();
                break;
        }

        return $process;
    }

    /**
     * Get destination path.
     *
     * @return string
     */
    public function getDestinationPath()
    {
        if ($this->path) {
            return $this->path;
        }

        return $this->repository->getComponentPath($this->getComponentName());
    }

    /**
     * Get git repo url.
     *
     * @return string|null
     */
    public function getRepoUrl()
    {
        switch ($this->type) {
            case 'github':
                return "git@github.com:{$this->name}.git";
                break;

            case 'github-https':
                return "https://github.com/{$this->name}.git";
                break;

            case 'bitbucket':
                return "git@bitbucket.org:{$this->name}.git";
                break;

            default:
                return;
                break;
        }
    }

    /**
     * Get branch name.
     *
     * @return string
     */
    public function getBranch()
    {
        return is_null($this->version) ? 'master' : $this->version;
    }

    /**
     * Get component name.
     *
     * @return string
     */
    public function getComponentName()
    {
        $parts = explode('/', $this->name);

        return Str::studly(end($parts));
    }

    /**
     * Get composer package name.
     *
     * @return string
     */
    public function getPackageName()
    {
        if (is_null($this->version)) {
            return $this->name . ':dev-master';
        }

        return $this->name . ':' . $this->version;
    }

    /**
     * Install the component via git.
     *
     * @return \Symfony\Component\Process\Process
     */
    public function installViaGit()
    {
        return new Process(sprintf(
            'cd %s && git clone %s %s && cd %s && git checkout %s',
            base_path(),
            $this->getRepoUrl(),
            $this->getDestinationPath(),
            $this->getDestinationPath(),
            $this->getBranch()
        ));
    }

    /**
     * Install the component via git subtree.
     *
     * @return \Symfony\Component\Process\Process
     */
    public function installViaSubtree()
    {
        return new Process(sprintf(
            'cd %s && git remote add %s %s && git subtree add --prefix=%s --squash %s %s',
            base_path(),
            $this->getComponentName(),
            $this->getRepoUrl(),
            $this->getDestinationPath(),
            $this->getComponentName(),
            $this->getBranch()
        ));
    }

    /**
     * Install the component via composer.
     *
     * @return \Symfony\Component\Process\Process
     */
    public function installViaComposer()
    {
        return new Process(sprintf(
            'cd %s && composer require %s',
            base_path(),
            $this->getPackageName()
        ));
    }
}
