<?php

namespace Domain\Shared\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ModelModified
{
    use Dispatchable, SerializesModels;

    // const KEY_PROPERTY = 'property';

    // const KEY_ORIGINAL_VALUE = 'originalValue';

    // const KEY_MODIFIED_VALUE = 'modifiedValue';

    public $roleType;

    public $userModel;

    public $new;

    public $change;

    /**
     * Create a new event instance.
     *
     * @param  mixed  $userModel
     */
    public function __construct(string $roleType, $userModel, bool $new, array $change)
    {
        $this->roleType = $roleType;
        $this->userModel = $userModel;
        $this->new = $new;
        $this->change = $change;
    }
}
