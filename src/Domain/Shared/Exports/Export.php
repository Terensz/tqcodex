<?php

namespace Domain\Shared\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

/**
 * Since Excel requires an object with this format, this is the general object we use for excel exports.
 * Just instantiate this with any Collection, and that will be okay.
 */
class Export implements FromCollection
{
    public $collection;

    public function __construct($collection)
    {
        $this->collection = $collection;
    }

    public function collection()
    {
        return $this->collection;
    }
}
