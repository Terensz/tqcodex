<?php

namespace Domain\Finance\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidContactEmail implements DataAwareRule, ValidationRule
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
        if ($this->data && isset($this->data['contactEmail'])) {
            if (! isset($this->data['contact_id']) || empty($this->data['contact_id'])) {
                $fail(__('customer.ContactEmailDoesNotBelongToAValidUser'));
            }
        }
    }
}
