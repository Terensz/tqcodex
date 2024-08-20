<?php

namespace Domain\User\Models\Base;

use Domain\Shared\Events\ModelSaved;
use Domain\Shared\Models\BaseModel;

class BaseLoggedModel extends BaseModel
{
    protected static function booted()
    {
        static::saved(function ($model) {
            ModelSaved::dispatch($model);
        });
    }
}
