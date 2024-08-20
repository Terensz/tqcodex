<?php

namespace Domain\Shared\Livewire\Base;

use Livewire\Component;

abstract class BaseLivewireComponent extends Component
{
    abstract public static function getComponentName(): string;

    // public function mount($entityObject)
    // {
    //     $this->checkComponentNameImplementation();
    // }

    // private function checkComponentNameImplementation()
    // {
    //     if (! method_exists(static::class, 'getComponentName')) {
    //         throw new \BadMethodCallException(sprintf(
    //             'The %s class must implement the getComponentName method.',
    //             static::class
    //         ));
    //     }
    // }
}
