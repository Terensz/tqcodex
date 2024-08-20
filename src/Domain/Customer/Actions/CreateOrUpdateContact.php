<?php

declare(strict_types=1);

namespace Domain\Customer\Actions;

use Domain\Admin\Models\User;
use Domain\Customer\Models\Contact;
use Domain\Customer\Models\ContactProfile;
use Domain\Shared\Actions\ReportError;
use Exception;

final class CreateOrUpdateContact
{
    /**
     * @param  array<string, mixed>  $data  (array of params)
     * @param  array<string, mixed>  $dataAddress  (array of params)
     * @param  array<string, string>  $dataIp  (array of params)
     */
    public function execute(
        array $data,
        array $dataAddress = [],
        array $dataIp = [],
        ?User $user = null
    ): ?Contact {
        try {
            $email = $data['email'];
            unset($data['email']);
            $contact = Contact::firstOrCreate(
                ['email' => $email]
            );

            if ($contact instanceof Contact) {
                $contact->fill($data);

                $contactProfile = $contact->getContactProfile();
                if ($contactProfile instanceof ContactProfile) {
                    $contactProfile->fill($data);
                    $contactProfile->save();
                    if (count($dataAddress) > 0) {
                        $dataAddress['title'] = __('address.PrimaryAddress');
                        $dataAddress['main'] = true;

                        $contactProfileAddress = $contactProfile->contactProfileAddresses()->firstOrCreate(
                            ['contact_id' => $contact->id],
                            $dataAddress
                        );
                        $contactProfileAddress->save();
                    }
                    if (count($dataIp) > 0) {
                        $dataIp['contact_id'] = $contact->id;
                        $contactProfile->contactProfileIps()->create($dataIp);
                    }
                }

                $contact->save();

                $this->attacheParents($contact, $user);

                return $contact;
            }
        } catch (Exception $e) {
            ReportError::sendReport($data, 'Failed to create contact: ', $e);
        }

        return null;
    }

    private function attacheParents(Contact $contact, ?User $user): void
    {
        //Attach to default account ['default' => true] and to default user [1]
        if (! $user instanceof User) {
            $user = User::find(1);
        }
        if ($user instanceof User) {
            $user->contacts()->attach($contact->id);
        }
    }
}
