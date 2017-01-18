<?php

namespace Consigliere\Components\Traits;

trait CanClearComponentsCache
{
    /**
     * Clear the components cache if it is enabled
     */
    public function clearCache()
    {
        if (config('components.cache.enabled') === true) {
            app('cache')->forget(config('components.cache.key'));
        }
    }
}
