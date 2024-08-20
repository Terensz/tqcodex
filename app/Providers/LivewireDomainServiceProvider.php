<?php

namespace App\Providers;

use Domain\Shared\Helpers\RouteHelper;
use Domain\Shared\Livewire\Base\BaseLivewireComponent;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Symfony\Component\Finder\Finder;

/**
 * This class auto-registers Livewire components in the src/Domain/{domainName}/Livewire directories.
 */
class LivewireDomainServiceProvider extends ServiceProvider
{
    /**
     * Automatically called by the framework, after registered at the config/app.php in the 'providers' section.
     *
     * @return void
     */
    public function register()
    {
        if (class_exists(Livewire::class)) {
            $this->start(RouteHelper::getDomainPath());
        }
    }

    private function start($paths)
    {
        $paths = array_unique(Arr::wrap($paths));

        $paths = array_filter($paths, function ($path) {
            return is_dir($path);
        });

        if (empty($paths)) {
            return;
        }

        $namespace = 'Domain\\';
        $componentDataArray = [];

        foreach ($paths as $path) {
            $finder = new Finder;

            $finder->in($path)->directories()->depth(0);

            foreach ($finder as $directory) {
                $livewirePath = $directory->getRealPath().DIRECTORY_SEPARATOR.'Livewire';

                if (is_dir($livewirePath)) {
                    $files = (new Finder)->in($livewirePath)->files()->depth(0);
                    foreach ($files as $domain) {
                        $componentClass = $namespace.str_replace(
                            ['/', '.php'],
                            ['\\', ''],
                            Str::after($domain->getRealPath(), realpath(RouteHelper::getDomainPath()).DIRECTORY_SEPARATOR)
                        );

                        if (is_subclass_of($componentClass, BaseLivewireComponent::class)) {
                            $componentName = $componentClass::getComponentName();
                            $componentDataArray[] = [
                                'componentName' => $componentName,
                                'componentClass' => $componentClass,
                            ];
                        }
                    }
                }
            }
        }

        /**
         * @todo: ezt kellene valahogyan cache-elni.
         */
        foreach ($componentDataArray as $componentData) {
            Livewire::component($componentData['componentName'], $componentData['componentClass']);
        }
    }
}
