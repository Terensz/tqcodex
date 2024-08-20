<?php

namespace Domain\Customer\Rules;

use Closure;
use Domain\Shared\Helpers\CountryHelper;
use Domain\User\Services\UserService;
use Exception;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class ExistingCountryId implements DataAwareRule, ValidationRule
{
    /**
     * All of the data under validation.
     *
     * @var array<string, mixed>
     */
    protected $data = [];

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
        if (! array_key_exists('contact_id', $this->data)) {
            $currentContact = UserService::getContact();
            $this->data['contact_id'] = $currentContact->id;
        }

        if (! array_key_exists('country_id', $this->data)) {
            throw new Exception('Data does not contain country_id key!');
        }

        $countryObject = CountryHelper::getCountryObjectById($this->data['country_id']);

        if (! $countryObject) {
            $fail(__('shared.InvalidCountry'));
        }
    }
}
