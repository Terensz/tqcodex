<?php

namespace Domain\Finance\Rules;

use Closure;
use Domain\Customer\Models\Organization;
use Domain\Finance\Models\PartnerOrg;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class AllowedPartnerOrgId implements DataAwareRule, ValidationRule
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
        $validPartnerOrg = null;
        $recordFound = Organization::where('id', $value)->first();

        if ($recordFound) {
            $validPartnerOrg = PartnerOrg::potentialPartner()->find($recordFound->id);
        }

        if (! $validPartnerOrg) {
            $fail(__('finance.InvalidPartnerOrgId'));
        }
    }
}
