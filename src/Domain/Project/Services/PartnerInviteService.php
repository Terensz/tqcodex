<?php

namespace Domain\Project\Services;

use Domain\Shared\Helpers\Crypter;

class PartnerInviteService
{
    public static function getInvitedRegisterLink($partnerEmail, $partnerName, $partnerContact)
    {
        return \Illuminate\Support\Facades\URL::temporarySignedRoute(
            'customer.contact.invited-register', now()->addHours(48), [
                'partnerEmail' => Crypter::encrypt($partnerEmail),
                'partnerName' => Crypter::encrypt($partnerName),
                'partnerContact' => Crypter::encrypt($partnerContact),
            ],
            absolute: true
        );

        // return \Illuminate\Support\Facades\URL::temporarySignedRoute(
        //     'customer.contact.invited-register', now()->addHours(48), [
        //         'partnerEmail' => $partnerEmail,
        //         'partnerName' => $partnerName,
        //         'partnerContact' => $partnerContact,
        //     ],
        //     absolute: true
        // )->secure();
    }
}
