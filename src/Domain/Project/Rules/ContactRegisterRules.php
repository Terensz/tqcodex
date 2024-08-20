<?php

declare(strict_types=1);

namespace Domain\Project\Rules;

use Domain\Customer\Models\ContactProfile;
use Domain\Customer\Rules\ContactRules;
use Domain\Customer\Rules\OrganizationRules;

class ContactRegisterRules
{
    /**
     * @return array<string, mixed>
     */
    public static function rules(?ContactProfile $model = null, $validationAdditionalData = []): array
    {
        $rules = ContactRules::rules();

        $orgRules = OrganizationRules::rules();

        foreach ($orgRules as $orgRuleKey => $orgRuleSet) {
            $rules['organization_'.$orgRuleKey] = $orgRuleSet;
        }

        /**
         * We are not using all the keys of the two models.
         */
        $keysToKeep = [
            'email',
            'title',
            'firstname',
            'middlename',
            'lastname',
            'phone',
            'mobile',
            'position',
            'language',
            'social_media',
            'organization_name',
            'organization_long_name',
            'organization_org_type',
            'organization_email',
            'organization_phone',
            'organization_taxpayer_id',
            'organization_taxid',
            'organization_eutaxid',
            'organization_vat_banned',
        ];

        $filteredRules = array_intersect_key($rules, array_flip($keysToKeep));

        return $filteredRules;
    }
}
