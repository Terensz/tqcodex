<?php

declare(strict_types=1);

namespace Domain\Customer\Actions;

use Domain\Admin\Models\User;
use Domain\Customer\Models\Contact;
use Domain\Customer\Models\Organization;
use Domain\Shared\Actions\ReportError;
use Exception;

final class CreateOrUpdateOrganization
{
    /**
     * @param  array<string, mixed>  $data  (array of params)
     * @param  array<string, mixed>  $dataAddress  (array of params)
     */
    public function execute(
        array $data,
        string $billingTo,
        array $dataAddress = [],
        ?Contact $contact = null,
        ?User $user = null
    ): ?Organization {
        try {
            $contactId = $contact->id ?? null;
            switch ($billingTo) {
                case 'huco':
                    $taxpayerId = $data['taxpayer_id'];
                    $organization = Organization::firstOrCreate(
                        ['taxpayer_id' => $taxpayerId]
                    );

                    break;

                case 'euco':
                    $eutaxid = $data['eutaxid'];
                    $organization = Organization::firstOrCreate(
                        ['eutaxid' => $eutaxid]
                    );

                    break;

                default:
                    //For any other non HU or non EU company
                    $companyEmail = $data['company_email'];
                    unset($data['company_email']);
                    $organization = Organization::firstOrCreate(
                        ['company_email' => $companyEmail]
                    );
            }
            $organization->fill($data);
            $organization->save();
            if (count($dataAddress) > 0) {
                $dataAddress['title'] = 'Elsődleges cím';
                $dataAddress['main'] = true;
                $organization->addresses()->firstOrCreate(
                    ['organization_id' => $organization->id],
                    $dataAddress
                );
            }

            $organization->contactProfiles()->attach($contactId);
            $this->attacheParents($organization, $user);

            return $organization;
        } catch (Exception $e) {
            // if an exception occurred
            ReportError::sendReport($data, 'Failed to create or update organization: ', $e);
        }

        return null;
    }

    private function attacheParents(Organization $organization, ?User $user): void
    {
        //Attach to default account ['default' => true] and to default user [1]
        if (! $user instanceof User) {
            $user = User::find(1);
        }
        if ($user instanceof User) {
            $user->organizations()->attach($organization->id);
        }
    }
}
