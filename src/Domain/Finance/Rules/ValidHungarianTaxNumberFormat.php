<?php

namespace Domain\Finance\Rules;

use Closure;
use Domain\Finance\Enums\CountyCode;
use Domain\Finance\Enums\VatCode;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidHungarianTaxNumberFormat implements DataAwareRule, ValidationRule
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
        $valid = false;
        $parts = explode('-', $value);
        if ((bool) preg_match('/^\d{8}-\d{1}-\d{2}$/', $value) && count($parts) == 3) {
            if (is_numeric($parts[0]) && in_array($parts[1], VatCode::getValueArray()->toArray()) && in_array($parts[2], CountyCode::getValueArray()->toArray())) {
                $valid = true;
            }
        }

        /**
         * Missing tax number is NOT invalid! It's missing. If it's a problem, make it required.
         */
        if (! $valid && ! empty($value)) {
            $fail(__('finance.InvalidHungarianTaxNumberFormat'));
        }
    }
}
