<?php

declare(strict_types=1);

namespace Domain\Customer\Actions;

use Domain\Customer\Models\Contact;
use Domain\Shared\Models\Country;
use Illuminate\Support\Str;
use Propaganistas\LaravelPhone\PhoneNumber;

/**
 * Collect Contact Data from the Order request and process data
 */
final class ContactData
{
    /**
     * @var array<string, mixed>
     */
    private array $contactData = [];

    /**
     * @var array<string, mixed>
     */
    private array $contactAddress = [];

    /**
     * @param  array<string, string>  $ipData
     */
    public function execute(mixed $validated, array $ipData): ?Contact
    {
        $data = collect($validated);
        $this->collectContactData($data);
        $this->collectContactAddress($data);
        $action = new CreateOrUpdateContact;

        return $action->execute(
            $this->contactData,
            $this->contactAddress,
            $ipData
        );
    }

    /**
     * @param  \Illuminate\Support\Collection<string, mixed>  $data
     */
    private function collectContactData($data): void
    {
        $phone = (string) new PhoneNumber(strval($data['phone']), strval($data['phone_country']));

        $this->contactData = [
            'lastname' => $data['lastname'],
            'firstname' => $data['firstname'],
            'phone' => $phone,
            'email' => $data['email'],
            'type' => $data['type'],
            'terms_ok' => $this->accepted($data['terms_ok'] ?? false),
            'news_ok' => $this->accepted($data['news_ok'] ?? false),
            'direct_sales_ok' => $this->accepted($data['direct_sales_ok'] ?? false),
        ];
    }

    /**
     * @param  \Illuminate\Support\Collection<string, mixed>  $data
     */
    private function collectContactAddress($data): void
    {
        if ($data['type'] === 'b2c') {
            $title = $data['form_name'].' számlacím';
            $countryParts = explode('|', strval($data['country_select']));
            $this->contactAddress = [
                'title' => $title,
                'postal_code' => $data['postal_code'],
                'city' => $data['city'],
                'lane' => $data['lane'],
                'region' => $data['region'],
                'country_code' => $countryParts[1],
                'country_id' => Country::getIdByCode($countryParts[1]),
            ];
        }
    }

    /**
     * Accepted: "yes", "on", "true", "1", 1 or true.
     */
    private function accepted(mixed $value): bool
    {
        if ($value === null) {
            return false;
        }
        if (Str::lower(strval($value)) === 'yes' ||
            Str::lower(strval($value)) === 'on' ||
            Str::lower(strval($value)) === 'true') {
            return true;
        }

        return (bool) ($value === '1' || $value === 1 || $value === true);
    }
}
