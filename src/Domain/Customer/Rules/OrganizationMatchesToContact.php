<?php

namespace Domain\Customer\Rules;

use Closure;
use Domain\Customer\Services\CustomerService;
use Domain\User\Services\UserService;
use Exception;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class OrganizationMatchesToContact implements DataAwareRule, ValidationRule
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
            // throw new Exception('Data does not contain contact_id key!');
        }

        if (! array_key_exists('organization_id', $this->data)) {
            throw new Exception('Data does not contain organization_id key!');
        }

        $orgFound = false;
        foreach (CustomerService::getOrganizations() as $organization) {
            if ($organization->id === $this->data['organization_id']) {
                $orgFound = true;
            }
        }

        if (! $orgFound) {
            $fail(__('customer.InvalidOrganization'));
        }
    }
}
