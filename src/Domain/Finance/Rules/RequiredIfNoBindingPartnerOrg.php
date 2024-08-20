<?php

namespace Domain\Finance\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class RequiredIfNoBindingPartnerOrg implements ValidationRule
{
    public $model;

    public function __construct($model)
    {
        $this->model = $model;
        // dump($this->model);exit;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // dump('RequiredIfNoBindingPartnerOrg');exit;
        $partner_org_id = $this->model->partner_org_id ?? null;

        // dump($partner_org_id);exit;

        // if ($partner_org_id) {
        //     $partnerOrg =
        // }
        // $partner_taxpayer_id = $this->model->partner_taxpayer_id ?? null;

        // // dump($partner_org_id, $partner_taxpayer_id);exit;

        // dump($partner_org_id);
        // dump($value);exit;
        if (empty($partner_org_id) && empty($value)) {
            $fail(__('validation.Required'));
        }
        // else {
        //     dump($partner_org_id);
        //     dump($value);exit;
        // }
    }
}
