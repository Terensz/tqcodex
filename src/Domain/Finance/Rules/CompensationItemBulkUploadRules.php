<?php

declare(strict_types=1);

namespace Domain\Finance\Rules;

class CompensationItemBulkUploadRules
{
    /**
     * @return array<string, mixed>
     */
    public static function rules($validationAdditionalData = []): array
    {
        return [
            'contactEmail' => [
                new ValidContactEmail,
            ],
            'organizationName' => [
                'required',
                new ValidOrganizationName,
            ],
            ...CompensationItemRules::rules($validationAdditionalData),
        ];
    }
}
