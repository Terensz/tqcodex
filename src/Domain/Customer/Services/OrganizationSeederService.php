<?php

namespace Domain\Customer\Services;

use Database\Factories\Customer\OrganizationFactory;
use Domain\Customer\Models\Contact;
use Domain\Customer\Models\ContactProfile;
use Domain\Customer\Models\OrgAddress;
use Domain\Customer\Models\Organization;
use Domain\Finance\Services\Nav\NavQuery;
use Domain\Finance\Services\OrganizationData;

class OrganizationSeederService
{
    public const FREQUENCY_ALL = 'All';

    public const FREQUENCY_RANDOM = 'Random';

    public $count = 100;

    public $registeredContactProfileOrganizations = [];

    public $contactProfileOrganizationSeederService;

    public $frequency = self::FREQUENCY_RANDOM;

    public static $nthOrgOfContact;

    public function __construct()
    {
        $this->contactProfileOrganizationSeederService = new ContactProfileOrganizationSeederService;
    }

    /**
     * Find the N-th Organization entity of the Contact with a given ID.
     *
     * The reason for this method:
     * There are some built-in test Customers who are frequently used in tests.
     * E.g.: 1st Customer is "István Holbok", 2nd Customer is "Ferenc Papp".
     * If we want to use e.g. the 2nd org of Ferenc Papp in a dataset, we must make our life easier
     * with a method helping us provide data of an exact Organization.
     * E.g.:
     * - In an OrganizationRule test it's handy to use existing Org name and tax number.
     * - In a CompensationItem test we also can use any org of the X-th Customer to fill the data of the Recorder or Partner Org.
     */
    public static function getNthOrgOfContact(int $contactId, int $orgIndex)
    {
        if (isset(self::$nthOrgOfContact[$contactId][$orgIndex])) {
            return self::$nthOrgOfContact[$contactId][$orgIndex];
        }

        $nthOrgOfContact = null;
        $orgs = null;
        $contact = Contact::find($contactId);
        if ($contact instanceof Contact) {
            $orgs = $contact->getOrganizations();
        }

        if ($orgs && is_array($orgs) && count($orgs) > $orgIndex) {
            $nthOrgOfContact = $orgs[$orgIndex];
        }

        self::$nthOrgOfContact[$contactId][$orgIndex] = $nthOrgOfContact;

        return $nthOrgOfContact;
    }

    public function seed()
    {
        for ($i = 0; $i < $this->count; $i++) {
            $org = OrganizationFactory::new()->createUntilNotTaken();
            if ($org instanceof Organization) {
                $orgAddress = OrgAddress::factory()->create();
                $orgAddress->organization_id = $org->id;
                $orgAddress->save();
                // dump($orgAddress);exit;
            }
        }
    }

    public function seedOrgsForContacts()
    {
        $contactProfiles = ContactProfile::all();
        foreach ($contactProfiles as $contactProfile) {
            $randomBoolean = rand(0, 1) === 1;
            if ($this->frequency === self::FREQUENCY_ALL || ($this->frequency === self::FREQUENCY_RANDOM && $randomBoolean)) {
                $this->contactProfileOrganizationSeederService->createContactProfileOrganizationWithRandomOrg($contactProfile);
            }
        }
    }

    public static function getExistingOrgData(int $index): ?OrganizationData
    {
        if ($index > count(self::getExistingOrgTaxNumber()) - 1 || $index < 0) {
            return null;
        }

        $taxNumberParts = explode('-', self::getExistingOrgTaxNumber($index));

        return NavQuery::queryTaxpayer($taxNumberParts[0]);
    }

    public static function getExistingOrgTaxNumber(?int $index = null): string|array
    {
        /*
        0 - "10220278-2-44" - "Profi Magyarország Zrt. v.a."
        1 - "10224612-2-13" - "K SPED Kft. f.a."
        2 - "10274019-2-41" - "BUDACONSUM KFT."
        4 - "10307078-2-44" - "TESCO-GLOBAL ZRT."
        5 - "10352281-2-43" - "TRIEX KFT"
        6 - "10485824-2-07" - "SPAR MAGYARORSZÁG"
        7 - "10488559-2-44" - "KRUPP ÉS TÁRSA KERESKEDELMI KFT"
        9 - "10534485-2-12" - "PALÓC NAGYKER KFT. f. a."
        10 - "10539868-2-13" - "KÜHNE + NAGEL KFT."
        11 - "10627510-2-07" - "PROCAR KFT"
        12 - "10939622-2-41" - "S.BEDI KFT"
        13 - "10969629-2-44" - "PENNY-MARKET KFT"
        14 - "11243942-2-15" - "Gávavencsellői Sütőüzem Kft f. a."
        15 - "11816672-2-41" - "CBA-Baldauf Kft."
        16 - "11930035-2-43" - "CBA-REMIZ-KER '99 KFT."
        17 - "12392908-2-41" - "CITY FOOD EURO Kft."
        22 - "13066907-2-43" - "NAGYMAROS TRADE KFT."
        23 - "13225265-2-42" - "CBA-Újhegy Kft."
        24 - "13237912-2-19" - "NAPCSILLAG KFT."
        25 - "13338037-2-44" - "AUCHAN MAGYARORSZÁG Kft."
        26 - "13352479-2-13" - "AMBROZIA FOOD KFT."
        27 - "13854641-2-43" - "PAN-PRODUKT Kft. f.a."
        28 - "14123472-2-41" - "VÁROSHÁZ - CENTRÁL KFT."
        29 - "14371376-2-13" - "Sarokház Cukrászat Kft. v.a."
        30 - "14675302-2-03" - "Nagyanyáink Sütödéje Kft."
        31 - "14772777-2-13" - "MEDITRIO Kft."
        32 - "14995679-2-42" - "KRIKOL SPEDÍCIÓ Kft"
        33 - "15329743-2-43" - "BCE"
        34 - "18120141-2-42" - ""
        35 - "18670457-1-13" - "EÁ.ÁLLATMENHELY"
        36 - "19662927-2-43" - "MÖSZ"
        37 - "21798333-2-13" - "BURGER SZIGET BT. kt. a."
        38 - "22761356-2-13" - "Mercurius Food Kft."
        39 - "22978112-2-13" - "EUR-TRANZIT Kft"
        40 - "24071259-2-03" - "DAO-PLUS Kft f. a."
        41 - "24195382-2-13" - "ARCTURUS 21 ALFA Kft. v.a."
        43 - "25072394-2-42" - "CBA-Grand Gourmet Kft."
        44 - "25577710-2-08" - "GYŐRI ÉDENKERT Kft."
        */
        $realTaxNumbers = [
            '10220278-2-44',
            '10224612-2-13',
            '10274019-2-41',
            '10307078-2-44',
            '10352281-2-43',
            '10485824-2-07',
            '10488559-2-44',
            '10534485-2-12',
            '10539868-2-13',
            '10627510-2-07',
            '10939622-2-41',
            '10969629-2-44',
            '11243942-2-15',
            '11816672-2-41',
            '11930035-2-43',
            '12392908-2-41',
            '12669679-2-03', // hibás!
            '12681624-2-41', // hibás!
            '12775664-2-13', // hibás!
            '13065298-2-08', // hibás!
            '13066907-2-43',
            '13225265-2-42',
            '13237912-2-19',
            '13338037-2-44',
            '13352479-2-13',
            '13854641-2-43',
            '14123472-2-41',
            '14371376-2-13',
            '14675302-2-03',
            '14772777-2-13',
            '14995679-2-42',
            '15329743-2-43',
            '18120141-2-42',
            '18670457-1-13',
            '19662927-2-43',
            '21798333-2-13',
            '22761356-2-13',
            '22978112-2-13',
            '24071259-2-03',
            '24195382-2-13',
            '24235189-2-03', // hibás!
            '25072394-2-42',
            '25577710-1-08',
        ];

        return $index === null ? $realTaxNumbers : $realTaxNumbers[$index];
    }
}
