<?php

namespace Consigliere\Components\Traits;

trait ComponentCommandTrait
{
    /**
     * Get the component name.
     *
     * @return string
     */
    public function getComponentName()
    {
        $component = $this->argument('component') ?: app('components')->getUsedNow();

        $component = app('components')->findOrFail($component);

        return $component->getStudlyName();
    }
}
