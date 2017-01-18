<?php

namespace Consigliere\Components\Publishing;

class LangPublisher extends Publisher
{
    /**
     * Determine whether the result message will shown in the console.
     *
     * @var bool
     */
    protected $showMessage = false;

    /**
     * Get destination path.
     *
     * @return string
     */
    public function getDestinationPath()
    {
        $name = $this->component->getLowerName();

        return base_path("resources/lang/{$name}");
    }

    /**
     * Get source path.
     *
     * @return string
     */
    public function getSourcePath()
    {
        return $this->getComponent()->getExtraPath(
            $this->repository->config('paths.generator.lang')
        );
    }
}
