<?php

declare(strict_types=1);

use Domain\Customer\Enums\CorporateForm;
use Domain\Customer\Enums\Location;
use Domain\Finance\Enums\CountyCode;
use Domain\Finance\Enums\VatCode;

/**
 * partner_taxpayer_id in format: 12345678-1-12
 */
$dataSet = [
    [
        [
            'is_banned' => 'alma',
            'name' => 'NAPCSILLAG KFT.',
            'long_name' => 'NAPCSILLAG KFT.',
            'taxpayer_id' => '13237912-2-19',
            'vat_code' => VatCode::TYPE_1->value,
            'county_code' => CountyCode::BEKES_1->value,
            'vat_verified_at' => null,
            'vat_banned' => false,
            'org_type' => CorporateForm::OPENINC->value,
            'country_code' => 'HU',
            'taxid' => null,
            'eutaxid' => null,
            'location' => Location::HU->value,
            'phone' => '+36705552233',
            'email' => 'alma@almail.com',
        ],
        'is_banned value is not correct.',
    ],
    [
        [
            'is_banned' => false,
            'name' => 'NAPCSILLAG KFT.',
            'long_name' => 'NAPCSILLAG KFT.',
            'taxpayer_id' => null,
            'vat_code' => VatCode::TYPE_1->value,
            'county_code' => CountyCode::BEKES_1->value,
            'vat_verified_at' => null,
            'vat_banned' => false,
            'org_type' => CorporateForm::OPENINC->value,
            'country_code' => 'HU',
            'taxid' => null,
            'eutaxid' => null,
            'location' => Location::HU->value,
            'phone' => '+36705552233',
            'email' => 'alma@almail.com',
        ],
        'Missing taxpayer_id.',
    ],
    [
        [
            'is_banned' => false,
            'name' => null,
            'long_name' => 'NAPCSILLAG KFT.',
            'taxpayer_id' => '13237912-2-19',
            'vat_code' => VatCode::TYPE_1->value,
            'county_code' => CountyCode::BEKES_1->value,
            'vat_verified_at' => null,
            'vat_banned' => false,
            'org_type' => CorporateForm::OPENINC->value,
            'country_code' => 'HU',
            'taxid' => null,
            'eutaxid' => null,
            'location' => Location::HU->value,
            'phone' => '+36705552233',
            'email' => 'alma@almail.com',
        ],
        'Missing name.',
    ],
    [
        [
            'is_banned' => false,
            'name' => 'NAPCSILLAG KFT.',
            'long_name' => 'NAPCSILLAG KFT.',
            'taxpayer_id' => '13237912-2-19',
            'vat_code' => 'alma',
            'county_code' => CountyCode::BEKES_1->value,
            'vat_verified_at' => null,
            'vat_banned' => false,
            'org_type' => CorporateForm::OPENINC->value,
            'country_code' => 'HU',
            'taxid' => null,
            'eutaxid' => null,
            'location' => Location::HU->value,
            'phone' => '+36705552233',
            'email' => 'alma@almail.com',
        ],
        'Invalid vat_code.',
    ],
    [
        [
            'is_banned' => false,
            'name' => 'NAPCSILLAG KFT.',
            'long_name' => 'NAPCSILLAG KFT.',
            'taxpayer_id' => '13237912-2-19',
            'vat_code' => VatCode::TYPE_1->value,
            'county_code' => CountyCode::BEKES_1->value,
            'vat_verified_at' => 'alma',
            'vat_banned' => false,
            'org_type' => CorporateForm::OPENINC->value,
            'country_code' => 'HU',
            'taxid' => null,
            'eutaxid' => null,
            'location' => Location::HU->value,
            'phone' => '+36705552233',
            'email' => 'alma@almail.com',
        ],
        'Invalid vat_verified_at.',
    ],
    [
        [
            'is_banned' => false,
            'name' => 'NAPCSILLAG KFT.',
            'long_name' => 'NAPCSILLAG KFT.',
            'taxpayer_id' => '13237912-2-19',
            'vat_code' => VatCode::TYPE_1->value,
            'county_code' => CountyCode::BEKES_1->value,
            'vat_verified_at' => null,
            'vat_banned' => 'alma',
            'org_type' => CorporateForm::OPENINC->value,
            'country_code' => 'HU',
            'taxid' => null,
            'eutaxid' => null,
            'location' => Location::HU->value,
            'phone' => '+36705552233',
            'email' => 'alma@almail.com',
        ],
        'Invalid vat_banned.',
    ],
    [
        [
            'is_banned' => false,
            'name' => 'NAPCSILLAG KFT.',
            'long_name' => 'NAPCSILLAG KFT.',
            'taxpayer_id' => '13237912-2-19',
            'vat_code' => VatCode::TYPE_1->value,
            'county_code' => CountyCode::BEKES_1->value,
            'vat_verified_at' => null,
            'vat_banned' => null,
            'org_type' => CorporateForm::OPENINC->value,
            'country_code' => 'HU',
            'taxid' => null,
            'eutaxid' => null,
            'location' => Location::HU->value,
            'phone' => '+36705552233',
            'email' => 'alma@almail.com',
        ],
        'Missing_vat_banned.',
    ],
    [
        [
            'is_banned' => false,
            'name' => 'NAPCSILLAG KFT.',
            'long_name' => 'NAPCSILLAG KFT.',
            'taxpayer_id' => '13237912-2-19',
            'vat_code' => VatCode::TYPE_1->value,
            'county_code' => CountyCode::BEKES_1->value,
            'vat_verified_at' => null,
            'vat_banned' => false,
            'org_type' => null,
            'country_code' => 'HU',
            'taxid' => null,
            'eutaxid' => null,
            'location' => Location::HU->value,
            'phone' => '+36705552233',
            'email' => 'alma@almail.com',
        ],
        'Missing org_type.',
    ],
    [
        [
            'is_banned' => false,
            'name' => 'NAPCSILLAG KFT.',
            'long_name' => 'NAPCSILLAG KFT.',
            'taxpayer_id' => '13237912-2-19',
            'vat_code' => VatCode::TYPE_1->value,
            'county_code' => CountyCode::BEKES_1->value,
            'vat_verified_at' => null,
            'vat_banned' => false,
            'org_type' => 'alma',
            'country_code' => 'HU',
            'taxid' => null,
            'eutaxid' => null,
            'location' => Location::HU->value,
            'phone' => '+36705552233',
            'email' => 'alma@almail.com',
        ],
        'Invalid org_type.',
    ],
    [
        [
            'is_banned' => false,
            'name' => 'NAPCSILLAG KFT.',
            'long_name' => 'NAPCSILLAG KFT.',
            'taxpayer_id' => '13237912-2-19',
            'vat_code' => VatCode::TYPE_1->value,
            'county_code' => CountyCode::BEKES_1->value,
            'vat_verified_at' => null,
            'vat_banned' => false,
            'org_type' => CorporateForm::OPENINC->value,
            'country_code' => 'alma',
            'taxid' => null,
            'eutaxid' => null,
            'location' => Location::HU->value,
            'phone' => '+36705552233',
            'email' => 'alma@almail.com',
        ],
        'Invalid country_code.',
    ],
    [
        [
            'is_banned' => false,
            'name' => 'NAPCSILLAG KFT.',
            'long_name' => 'NAPCSILLAG KFT.',
            'taxpayer_id' => '13237912-2-19',
            'vat_code' => VatCode::TYPE_1->value,
            'county_code' => CountyCode::BEKES_1->value,
            'vat_verified_at' => null,
            'vat_banned' => false,
            'org_type' => CorporateForm::OPENINC->value,
            'country_code' => 'HU',
            'taxid' => null,
            'eutaxid' => null,
            'location' => null,
            'phone' => '+36705552233',
            'email' => 'alma@almail.com',
        ],
        'Missing location.',
    ],
    [
        [
            'is_banned' => false,
            'name' => 'NAPCSILLAG KFT.',
            'long_name' => 'NAPCSILLAG KFT.',
            'taxpayer_id' => '13237912-2-19',
            'vat_code' => VatCode::TYPE_1->value,
            'county_code' => CountyCode::BEKES_1->value,
            'vat_verified_at' => null,
            'vat_banned' => false,
            'org_type' => CorporateForm::OPENINC->value,
            'country_code' => 'HU',
            'taxid' => null,
            'eutaxid' => null,
            'location' => 'alma',
            'phone' => '+36705552233',
            'email' => 'alma@almail.com',
        ],
        'Invalid location.',
    ],
    [
        [
            'is_banned' => false,
            'name' => 'NAPCSILLAG KFT.',
            'long_name' => 'NAPCSILLAG KFT.',
            'taxpayer_id' => '13237912-2-19',
            'vat_code' => VatCode::TYPE_1->value,
            'county_code' => CountyCode::BEKES_1->value,
            'vat_verified_at' => null,
            'vat_banned' => false,
            'org_type' => CorporateForm::OPENINC->value,
            'country_code' => 'HU',
            'taxid' => null,
            'eutaxid' => null,
            'location' => Location::HU->value,
            'phone' => 'alma',
            'email' => 'alma@almail.com',
        ],
        'Phone has wrong format.',
    ],
    [
        [
            'is_banned' => false,
            'name' => 'Alma Nyrt.',
            'long_name' => 'Alma Nyitott Részvénytársaság',
            'taxpayer_id' => '22345011-1-42',
            'vat_code' => VatCode::TYPE_1->value,
            'county_code' => CountyCode::BEKES_1->value,
            'vat_verified_at' => null,
            'vat_banned' => false,
            'org_type' => CorporateForm::OPENINC->value,
            'country_code' => 'HU',
            'taxid' => null,
            'eutaxid' => null,
            'location' => Location::HU->value,
            'phone' => '+36705552233',
            'email' => 'alma@',
        ],
        'Email has wrong format.',
    ],
];
dataset('OrganizationDataWrong', $dataSet);
