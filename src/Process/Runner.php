<?php

namespace Consigliere\Components\Process;

use Consigliere\Components\Contracts\RunableInterface;
use Consigliere\Components\Repository;

class Runner implements RunableInterface
{
    /**
     * The component instance.
     *
     * @var \Consigliere\Components\Repository
     */
    protected $component;

    /**
     * The constructor.
     *
     * @param \Consigliere\Components\Repository $component
     */
    public function __construct(Repository $component)
    {
        $this->component = $component;
    }


    /**
     * Run the given command.
     *
     * @param string $command
     */
    public function run($command)
    {
        passthru($command);
    }
}
