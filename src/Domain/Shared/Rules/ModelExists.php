<?php

namespace Domain\Shared\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ModelExists implements ValidationRule
{
    public $model;

    public function __construct($model)
    {
        // $this->roleType = $roleType;
        $this->model = $model;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $this->model || ! $this->model->exists) {
            $fail('shared.ModelDoesNotExist');
        }
    }
}
