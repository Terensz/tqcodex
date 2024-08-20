<?php

namespace Domain\Customer\Builders;

use Domain\Customer\Models\Contact;

/**
 * Ez nem javasolt használat, teljesen elbonylítja a lényeget
 * Depricated !!!
 */
class ContactDeprBuilder
{
    public static function getListQuery()
    {
        // return Contact::query();

        return Contact::query()
            ->leftJoin('contactprofiles', 'contacts.id', '=', 'contactprofiles.contact_id')
            ->selectRaw('contacts.*, contactprofiles.*, contacts.id as id');
    }

    public static function findValidContact($email)
    {
        // return Contact::query();

        return Contact::query()
            ->leftJoin('contactprofiles', 'contacts.id', '=', 'contactprofiles.contact_id')
            ->where('contactprofiles.email', $email)
            ->whereNotNull('contacts.email_verified_at');
    }
}
