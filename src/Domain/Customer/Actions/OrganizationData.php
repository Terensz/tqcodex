<?php

declare(strict_types=1);

namespace Domain\Customer\Actions;

use Domain\Customer\Models\Contact;
use Domain\Customer\Models\Organization;
use Domain\Shared\Models\Country;
use Propaganistas\LaravelPhone\PhoneNumber;

/**
 * Collect Organization Data from the Order request and process data
 */
final class OrganizationData
{
    /**
     * @var array<string, mixed>
     */
    private array $orgData = [];

    /**
     * @var array<string, mixed>
     */
    private array $orgAddress = [];

    public function execute(mixed $validated, ?Contact $contact): ?Organization
    {
        $data = collect($validated);
        if ($data['type'] === 'b2b') {
            $this->collectOrganizationData($data);
            $action = new CreateOrUpdateOrganization;

            return $action->execute(
                $this->orgData,
                strval($data['billing_to']),
                $this->orgAddress,
                $contact
            );
        }

        return null;
    }

    /**
     * @param  \Illuminate\Support\Collection<string, mixed>  $data
     */
    private function collectOrganizationData($data): void
    {
        $phone = (string) new PhoneNumber(strval($data['phone']), strval($data['phone_country']));

        $this->orgData = [
            'name' => $data['name'],
            'company_email' => $data['email'],
            'company_phone' => $phone,
        ];
        $title = $data['form_name'].' számlacím';

        $this->orgAddress['title'] = $title;

        switch ($data['billing_to']) {
            case 'huco':
                $this->collectHuCompany($data);
                $this->collectHuCompanyAddress($data);

                break;

            case 'euco':
                $this->collectEUCompany($data);
                $this->collectEUCompanyAddress($data);

                break;

            default:
                //Any other non HU and non EU company
                $this->collectOtherCompany($data);
                $this->collectOtherCompanyAddress($data);
        }
    }

    /**
     * @param  \Illuminate\Support\Collection<string, mixed>  $data
     */
    private function collectHuCompany($data): void
    {
        if (isset($data['long_name'])) {
            $this->orgData = [...$this->orgData, ...[
                'long_name' => $data['long_name'],
                'taxpayer_id' => $data['taxpayer_id'],
                'vat_code' => $data['vat_code'],
                'county_code' => $data['county_code'],
                'vat_verified_at' => $data['vat_verified_at'],
                'org_type' => $data['org_type'],
                'address_type' => $data['address_type'],
                'location' => 'HU',
            ],
            ];
        }
    }

    /**
     * @param  \Illuminate\Support\Collection<string, mixed>  $data
     */
    private function collectEUCompany($data): void
    {
        $this->orgData = [...$this->orgData, ...[
            'eutaxid' => $data['eutaxid'],
            'country_code' => $data['country_code'],
            'location' => 'EU',
        ],
        ];
    }

    /**
     * @param  \Illuminate\Support\Collection<string, mixed>  $data
     */
    private function collectOtherCompany($data): void
    {
        $this->orgData = [...$this->orgData, ...[
            'taxid' => $data['taxid'],
            'location' => 'EUK',
        ],
        ];
    }

    /**
     * Data sample:
     * "billing_to": "huco",
     * "business_type": "b2b",
     * "location": "HU",
     * "taxpayer_id": "23793956-2-43",
     * "vat_code": "2",
     * "county_code": "43",
     * "org_type": "ORGANIZATION",
     * "address_type": "HQ",
     * "country_code": "HU",
     * "vat_verified_at": "2022-08-02",
     * "long_name": "TRIANITY SZOLGÁLTATÓ, KUTATÓ-FEJLESZTŐ ÉS KERESKEDELMI KORLÁTOLT FELELŐSSÉGŰ TÁRSASÁG",
     * "name": "TRIANITY Kft.",
     * "postal_code": "1119",
     * "city": "Budapest",
     * "street_name": "Andor",
     * "public_place_category": "utca",
     * "number": "21",
     * "building": "C",
     * "floor": "földszint",
     * "door": "1",
     *
     * @param  \Illuminate\Support\Collection<string, mixed>  $data
     */
    private function collectHuCompanyAddress($data): void
    {
        $this->orgAddress = [...$this->orgAddress, ...[
            'postal_code' => $data['postal_code'],
            'city' => $data['city'],
            'lane' => $data['lane'] ?? null,
            'region' => $data['region'] ?? null,
            'country_code' => 'HU',
            'country_id' => Country::getIdByCode('HU'),
            'address' => ' ',
        ],
        ];

        if (isset($data['street_name'])) {
            $this->orgAddress = [...$this->orgAddress, ...[
                'street_name' => $data['street_name'],
                'public_place_category' => $data['public_place_category'],
                'number' => $data['number'],
                'building' => $data['building'],
                'floor' => $data['floor'],
                'door' => $data['door'],
            ],
            ];
        }
    }

    /**
     * Data sample of EU Company:
     * "billing_to": "euco",
     * "address": "Jesenského 2328/60, 96001 Zvolen, Slovensko",
     * "business_type": "b2b",
     * "location": "EU",
     * "eutaxid": "SK2121065980",
     * "country_code": "SK",
     * "name": "Pro-Tech Shop, s. r. o.",
     * "postal_code": "96001",
     * "city": "Zvolen",
     * "lane": "Jesenského 2328/60",
     * "region": null,
     * "country": "Slovakia",
     * "comment": "Teszt",
     * "terms_ok": "on",
     *
     * @param  \Illuminate\Support\Collection<string, mixed>  $data
     */
    private function collectEUCompanyAddress($data): void
    {
        $this->orgAddress = [...$this->orgAddress, ...[
            'postal_code' => $data['postal_code'],
            'city' => $data['city'],
            'lane' => $data['lane'],
            'region' => $data['region'],
            'country_code' => $data['country_code'],
            'country_id' => Country::getIdByCode(strval($data['country_code'])),
        ],
        ];
    }

    /**
     * @param  \Illuminate\Support\Collection<string, mixed>  $data
     */
    private function collectOtherCompanyAddress($data): void
    {
        $countryParts = explode('|', strval($data['country_select']));

        $this->orgAddress = [...$this->orgAddress, ...[
            'postal_code' => $data['postal_code'],
            'city' => $data['city'],
            'lane' => $data['lane'],
            'region' => $data['region'],
            'country_code' => $countryParts[1],
            'country_id' => Country::getIdByCode($countryParts[1]),
        ],
        ];
    }
}
