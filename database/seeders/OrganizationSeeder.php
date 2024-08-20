<?php

declare(strict_types=1);

namespace Database\Seeders;

use Domain\Customer\Enums\CorporateForm;
use Domain\Customer\Enums\Location;
use Domain\Customer\Models\ContactProfile;
use Domain\Customer\Models\OrgAddress;
use Domain\Customer\Models\Organization;
use Domain\Customer\Services\ContactProfileOrganizationSeederService;
use Domain\Customer\Services\OrganizationSeederService;
use Domain\Shared\Models\Country;
use Illuminate\Database\Seeder;

final class OrganizationSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $contactProfileOrganizationSeederService = new ContactProfileOrganizationSeederService;
        $contactProfileA = ContactProfile::where(['email' => 'terencecleric@gmail.com'])->first();
        $contactProfileB = ContactProfile::where(['email' => 'admin@trianity.dev'])->first();

        // Próba Nyilvános Részvénytársaság

        $org = Organization::create([
            'is_banned' => false,
            'name' => 'TESCO-GLOBAL ZRT.',
            'long_name' => 'TESCO-GLOBAL ZRT.',
            'taxpayer_id' => '10307078-2-44',
            // 'vat_code' => '',
            // 'county_code' => '',
            'vat_banned' => false,
            'org_type' => CorporateForm::OPENINC,
            'email' => 'probanyrt@gmail.com',
            'phone' => '+36309999991',
            'location' => Location::HU->name,
        ]);

        OrgAddress::create([
            'organization_id' => $org->id,
            'title' => 'TESCO-GLOBAL ZRT. székhelye',
            'main' => true,
            'country_id' => Country::getIdByCode('HU'),
            'postal_code' => '1138',
            'city' => 'Budapest',
            'street_name' => 'Alma',
            'public_place_category' => 'utca',
            'number' => '125',
            'building' => 'A',
            'floor' => 'FSZ',
            'door' => '4',
            // 'address' => '1138 Budapest, Alma utca 125.',
        ]);

        OrgAddress::create([
            'organization_id' => $org->id,
            'title' => 'TESCO-GLOBAL ZRT. ügyfélkapcsolati irodája',
            'main' => false,
            'country_id' => Country::getIdByCode('HU'),
            'postal_code' => '2030',
            'city' => 'Érd',
            'street_name' => 'Körte',
            'public_place_category' => 'utca',
            'number' => '1',
        ]);

        $contactProfileOrganizationSeederService->createContactProfileOrganization($org->id, $contactProfileB);

        // Parányi Betéti Társaság

        $org = Organization::create([
            'is_banned' => false,
            'name' => 'NAPCSILLAG KFT.',
            'long_name' => 'NAPCSILLAG KFT.',
            'taxpayer_id' => '13237912-2-19',
            // 'vat_code' => '',
            // 'county_code' => '',
            'vat_banned' => false,
            'org_type' => CorporateForm::LPS,
            'email' => 'paranyibt@gmail.com',
            'phone' => '+36309999992',
            'location' => Location::HU->name,
        ]);

        OrgAddress::create([
            'organization_id' => $org->id,
            'title' => 'NAPCSILLAG KFT. székhelye',
            'main' => true,
            'country_id' => Country::getIdByCode('HU'),
            'postal_code' => '5000',
            'city' => 'Szolnok',
            'street_name' => 'Petőfi Sándor',
            'public_place_category' => 'utca',
            'number' => '12',
        ]);

        $contactProfileOrganizationSeederService->createContactProfileOrganization($org->id, $contactProfileB);

        // Szaturnusz Egyesület

        $org = Organization::create([
            'is_banned' => false,
            'name' => 'Nagyanyáink Sütödéje Kft.',
            'long_name' => 'Nagyanyáink Sütödéje Kft.',
            'taxpayer_id' => '14675302-2-03',
            // 'vat_code' => '',
            // 'county_code' => '',
            'vat_banned' => false,
            'org_type' => CorporateForm::ASSOC,
            'email' => 'szaturnuszegyesulet@gmail.com',
            'phone' => '+36309999993',
            'location' => Location::HU->name,
        ]);

        OrgAddress::create([
            'organization_id' => $org->id,
            'title' => 'Nagyanyáink Sütödéje Kft. székhelye',
            'main' => true,
            'country_id' => Country::getIdByCode('HU'),
            'postal_code' => '1120',
            'city' => 'Budapest',
            'street_name' => 'Váci',
            'public_place_category' => 'út',
            'number' => '231',
        ]);

        // TRIEX KFT

        $org = Organization::create([
            'is_banned' => false,
            'name' => 'TRIEX KFT', // Ex-TRIEX KFT
            'long_name' => 'TRIEX KFT',
            'taxpayer_id' => '10352281-2-43',
            // 'vat_code' => '',
            // 'county_code' => '',
            'vat_banned' => false,
            'org_type' => CorporateForm::ASSOC,
            'email' => 'calistokft@gmail.com',
            'phone' => '+36309999994',
            'location' => Location::HU->name,
        ]);

        OrgAddress::create([
            'organization_id' => $org->id,
            'title' => 'TRIEX KFT székhelye',
            'main' => true,
            'country_id' => Country::getIdByCode('HU'),
            'postal_code' => '1120',
            'city' => 'Budapest',
            'street_name' => 'Váci',
            'public_place_category' => 'út',
            'number' => '231',
        ]);

        OrgAddress::create([
            'organization_id' => $org->id,
            'title' => 'TRIEX KFT számlázási címe',
            'main' => false,
            'country_id' => Country::getIdByCode('HU'),
            'postal_code' => '2040',
            'city' => 'Budaörs',
            'street_name' => 'Alma',
            'public_place_category' => 'utca',
            'number' => '2',
        ]);

        $contactProfileOrganizationSeederService->createContactProfileOrganization($org->id, $contactProfileA);

        $org = Organization::create([
            'is_banned' => false,
            'name' => 'SPAR MAGYARORSZÁG', // Ex-Io Kft.
            'long_name' => 'SPAR MAGYARORSZÁG',
            'taxpayer_id' => '10485824-2-07',
            // 'vat_code' => '',
            // 'county_code' => '',
            'vat_banned' => false,
            'org_type' => CorporateForm::ASSOC,
            'email' => 'iokft@gmail.com',
            'phone' => '+36309999995',
            'location' => Location::HU->name,
        ]);

        OrgAddress::create([
            'organization_id' => $org->id,
            'title' => 'SPAR MAGYARORSZÁG székhelye',
            'main' => true,
            'country_id' => Country::getIdByCode('HU'),
            'postal_code' => '1137',
            'city' => 'Budapest',
            'street_name' => 'Váci',
            'public_place_category' => 'lépcső',
            'number' => '526',
        ]);

        $contactProfileOrganizationSeederService->createContactProfileOrganization($org->id, $contactProfileA);

        // Phobos ZRT.

        $org = Organization::create([
            'is_banned' => false,
            'name' => 'GYŐRI ÉDENKERT Kft.',
            'long_name' => 'GYŐRI ÉDENKERT Kft.',
            'taxpayer_id' => '25577710-2-08',
            // 'vat_code' => '',
            // 'county_code' => '',
            'vat_banned' => false,
            'org_type' => CorporateForm::ASSOC,
            'email' => 'phoboszrt@gmail.com',
            'phone' => '+36309999995',
            'location' => Location::HU->name,
        ]);

        OrgAddress::create([
            'organization_id' => $org->id,
            'title' => 'GYŐRI ÉDENKERT Kft. székhelye',
            'main' => true,
            'country_id' => Country::getIdByCode('HU'),
            'postal_code' => '1141',
            'city' => 'Budapest',
            'street_name' => 'Bükkfa',
            'public_place_category' => 'út',
            'number' => '2',
        ]);

        // dump($org);exit;

        // Randomness
        /**
         * In this project (Elszamolo) we will put some randomness into creating companies and boundings between them and contacts.
         * We will
         */
        $organizationSeederService = new OrganizationSeederService;
        $organizationSeederService->count = 150;
        $organizationSeederService->seed();

        $organizationSeederService->seedOrgsForContacts();
    }
}
