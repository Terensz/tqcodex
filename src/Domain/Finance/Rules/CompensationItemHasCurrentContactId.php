<?php

namespace Domain\Finance\Rules;

use Closure;
use Domain\User\Services\UserService;
use Exception;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class CompensationItemHasCurrentContactId implements DataAwareRule, ValidationRule
{
    /**
     * All of the data under validation.
     *
     * @var array<string, mixed>
     */
    protected $data = [];

    /**
     * Set the data under validation.
     *
     * @param  array<string, mixed>  $data
     */
    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! array_key_exists('contact_id', $this->data)) {
            throw new Exception('Data does not contain contact_id key!');
        }

        if (! UserService::getContact() || $this->data['contact_id'] !== UserService::getContact()->id) {
            $fail(__('finance.ThisCompensationItemIsNotOwned'));
        }
    }
}
