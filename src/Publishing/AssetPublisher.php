<?php

namespace Consigliere\Components\Publishing;

class AssetPublisher extends Publisher
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
        return $this->repository->assetPath($this->component->getLowerName());
    }

    /**
     * Get source path.
     *
     * @return string
     */
    public function getSourcePath()
    {
        return $this->getComponent()->getExtraPath(
            $this->repository->config('paths.generator.assets')
        );
    }
}
