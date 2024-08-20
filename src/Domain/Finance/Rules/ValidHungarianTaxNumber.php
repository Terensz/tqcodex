<?php

namespace Domain\Finance\Rules;

use Closure;
use Domain\Customer\Rules\OrganizationRules;
use Domain\Finance\Services\Nav\NavQuery;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidHungarianTaxNumber implements DataAwareRule, ValidationRule
{
    /**
     * All of the data under validation.
     *
     * @var array<string, mixed>
     */
    protected $data = [];

    protected $ruleClass;

    protected $property;

    public function __construct(string $ruleClass, $property)
    {
        $this->ruleClass = $ruleClass;
        $this->property = $property;
    }

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
        $valueParts = explode('-', $value);
        $taxpayerData = null;
        if (count($valueParts) == 3 && is_numeric($valueParts[0]) && strlen($valueParts[0]) == 8) {
            $taxpayerData = NavQuery::queryTaxpayer($valueParts[0]);
        }

        if (! empty($value)) {
            if (! $taxpayerData || $taxpayerData->vatCode != $valueParts[1] || $taxpayerData->countyCode != $valueParts[2]) {
                $fail(__('finance.InvalidHungarianTaxNumber'));
            } else {
                if ($this->ruleClass == OrganizationRules::class && $this->property == 'taxpayer_id' && isset($this->data['name'])) {
                    if ($this->data['name'] != $taxpayerData->shortName) {
                        $fail(__('finance.InvalidHungarianTaxNumber2'));
                    }
                }
                if ($this->ruleClass == CompensationItemRules::class && $this->property == 'partner_taxpayer_id' && isset($this->data['partner_name'])) {
                    if ($this->data['partner_name'] != $taxpayerData->shortName) {
                        // dump($this->data['partner_name']);
                        $fail(__('finance.InvalidHungarianTaxNumber3'));
                    }
                }
            }
        }
    }
}
