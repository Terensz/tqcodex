<?php

namespace Domain\Finance\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class EitherPartnerOrgIdOrTaxpayerId implements DataAwareRule, ValidationRule
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

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $partner_org_id = $this->data['partner_org_id'] ?? null;
        $partner_taxpayer_id = $this->data['partner_taxpayer_id'] ?? null;

        if (is_null($partner_org_id) && is_null($partner_taxpayer_id)) {
            $fail('project.EitherPartnerOrgIdOrTaxpayerId')->translate();
        }
    }
}
