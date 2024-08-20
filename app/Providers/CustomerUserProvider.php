<?php

namespace App\Providers;

use Domain\Customer\Models\Contact;
use Domain\Customer\Models\ContactProfile;
use Illuminate\Auth\EloquentUserProvider;

class CustomerUserProvider extends EloquentUserProvider
{
    public function retrieveByCredentials(array $credentials)
    {
        $contactModel = new Contact;
        $contactProfileModel = new ContactProfile;

        $contactData = [];
        $contactProfileData = [];

        foreach ($credentials as $property => $value) {
            if ($property === 'id') {
                $contactData[$property] = $value;
            } elseif (! in_array($property, ['password']) && in_array($property, $contactModel->getFillable())) {
                $contactData[$property] = $value;
            } elseif (in_array($property, $contactProfileModel->getFillable())) {
                $contactProfileData[$property] = $value;
            }
        }

        $contact = null;
        if (! empty($contactData) && ! empty($contactProfileData)) {
            $query = ContactProfile::query()
                ->where($contactProfileData)
                ->whereHas('contact', function ($query) use ($contactData) {
                    $query->where($contactData);
                });
            $contactProfile = $query->first();
            $contact = $contactProfile ? $contactProfile->getContact() : null;
        } elseif (! empty($contactData)) {
            $query = Contact::query()->where($contactData);
            $contact = $query->first();
        } elseif (! empty($contactProfileData)) {
            $query = ContactProfile::query()->where($contactProfileData);
            $contactProfile = $query->first();
            $contact = $contactProfile ? $contactProfile->getContact() : null;
        }

        if ($contact && ! $contact instanceof Contact) {
            $contact = null;
            throw new \Exception('Invalid contact');
        }

        return $contact;
    }
}
