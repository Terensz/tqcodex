<?php

declare(strict_types=1);

namespace Domain\Finance\Rules;

use Domain\Customer\Enums\Location;
use Domain\Customer\Rules\OrganizationMatchesToContact;
use Domain\Customer\Services\OrganizationService;
use Domain\Finance\Enums\Currency;
use Domain\Finance\Enums\InvoiceType;
use Domain\Finance\Enums\PaymentMode;
use Domain\Shared\Enums\TrueOrFalse;
use Domain\Shared\Rules\MaxOnceInArray;
use Domain\Shared\Rules\ValidEnum;
use Illuminate\Validation\Rule;

class CompensationItemRules
{
    /**
     * @return array<string, mixed>
     */
    public static function rules($validationAdditionalData = []): array
    {
        return [
            // 'contactEmail' => [
            //     new ValidContactEmail($model),
            // ],
            // 'organizationName' => [
            //     new ValidOrganizationName($model),
            // ],
            'contact_id' => [
                'required',
                'numeric',
                new CompensationItemHasCurrentContactId,
            ],
            'organization_id' => [
                'required',
                new OrganizationMatchesToContact,
            ],
            'invoice_id_for_compensation' => [
                'required',
                new UniqueInvoiceIdForCompensation,
                new MaxOnceInArray(
                    ($validationAdditionalData['collections']['invoice_id_for_compensation']['data'] ?? []),
                    ($validationAdditionalData['collections']['invoice_id_for_compensation']['errorMessage'] ?? null),
                ),
            ],
            'invoice_internal_id' => [
            ],
            'due_date' => [
                'required',
                'date_format:Y-m-d',
            ],
            'invoice_date' => [
                'nullable',
                'date_format:Y-m-d',
            ],
            'fulfilment_date' => [
                'nullable',
                'date_format:Y-m-d',
            ],
            'late_interest_rate' => [
                'required',
                'numeric',
            ],
            'late_interest_amount' => [
                'required',
                'integer',
            ],
            'invoice_amount' => [
                'required',
            ],
            'invoice_type' => [
                'required',
                // new ValidEnum(InvoiceType::class, 'finance'),
                new ValidEnum(InvoiceType::class),
            ],
            'payment_mode' => [
                'required',
                new ValidEnum(PaymentMode::class),
            ],
            'currency' => [
                'required',
                new ValidEnum(Currency::class),
            ],
            'is_part_amount' => [
                'required',
                Rule::in(TrueOrFalse::getPossibleValidationValues()),
                // new ValidBoolString(),
            ],
            'is_disputed' => [
                'required',
                Rule::in(TrueOrFalse::getPossibleValidationValues()),
            ],
            // 'partner_unique_id' => [
            //     // 'required',
            // ],
            'partner_org_id' => [
                'nullable',
                // 'exclude_if:valid_taxpayer_id,true',
                'required_without:partner_name',
                'integer',
                'numeric',
                'gt:0',
                new AllowedPartnerOrgId,
            ],
            'partner_location' => [
                'required_without:partner_org_id',
                new ValidEnum(Location::class),
            ],
            'partner_name' => [
                // 'required',
                'required_without:partner_org_id',
                'max:'.OrganizationService::NAME_MAX_DIGITS,
            ],
            // 'partner_taxpayer_id' => [
            //     'exclude_if:valid_partner_org_id,true',
            //     'required_without:partner_org_id',
            //     new ValidHungarianTaxNumber(),
            //     // 'max:'.OrganizationService::TAXPAYER_ID_MAX_DIGITS,
            // ],
            'partner_taxpayer_id' => [
                'required_if:partner_location,'.Location::HU->value,
                // new ValidHungarianTaxNumberFormat(),
                new ValidHungarianTaxNumber(CompensationItemRules::class, 'partner_taxpayer_id'),
            ],
            'partner_eutaxid' => [
                'required_if:partner_location,'.Location::EU->value,
                new ValidEuTaxId,
            ],
            'partner_email' => [
                'nullable',
                'required_without_all:partner_org_id',
                'email:rfc,dns',
                // new RequiredIfNoBindingPartnerOrg($model),
                // 'required',
            ],
            'partner_phone' => [
                'nullable', //
                'phone:INTERNATIONAL,HU',
            ],
            'partner_contact' => [
                'nullable',
                'required_with:partner_email,partner_phone',
            ],
            'is_compensed' => [
                'required',
                Rule::in(TrueOrFalse::getPossibleValidationValues()),
            ],
        ];
    }
}
