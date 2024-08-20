<?php

declare(strict_types=1);

namespace Domain\Customer\Actions;

use Domain\Customer\Models\Contact;
use Illuminate\Database\Eloquent\Collection;

final class SetAllReferralKeys
{
    public static function execute(): void
    {
        Contact::chunk(200, function (Collection $contacts) {
            foreach ($contacts as $contact) {
                if ($contact instanceof Contact) {
                    if (SetReferralKey::execute($contact)) {
                        echo "Referal key was set for Contact ID: {$contact->id}"."\n";
                    } else {
                        echo "!!! Error: No referal key for Contact ID: {$contact->id}"."\n";
                    }
                }
            }
        });
    }
}
