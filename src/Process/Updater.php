<?php

namespace Consigliere\Components\Process;

class Updater extends Runner
{
    /**
     * Update the dependencies for the specified component by given the component name.
     *
     * @param string $component
     */
    public function update($component)
    {
        $component = $this->component->findOrFail($component);

        $packages = $component->getComposerAttr('require', []);

        chdir(base_path());

        foreach ($packages as $name => $version) {
            $package = "\"{$name}:{$version}\"";

            $this->run("composer require {$package}");
        }
    }
}
