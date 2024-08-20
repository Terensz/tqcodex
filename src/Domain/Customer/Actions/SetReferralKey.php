<?php

declare(strict_types=1);

namespace Domain\Customer\Actions;

use Domain\Customer\Models\Contact;
use Exception;
use Trianity\Sqids\Facades\Sqids;

final class SetReferralKey
{
    public static function execute(Contact $contact): bool
    {
        if (is_null($contact->referral_key)) {
            try {

                return self::setKey($contact);

            } catch (Exception $e) {
                echo 'Hiba történt: ',  $e->getMessage(), "\n";
                echo 'Ismétlés következik:'."\n";
                try {
                    return self::setKey($contact);
                } catch (Exception $e) {
                    echo 'Újabb hiba történt: ',  $e->getMessage(), "\n";
                    echo 'Ismétlés már nincs, Contact ID: '.$contact->id."\n";
                }
            }
        }

        return false;
    }

    protected static function setKey(Contact $contact): bool
    {
        $refdata = Sqids::encode([$contact->id]);
        $ids = Sqids::decode($refdata);
        if (in_array($contact->id, $ids)) {
            $contact->referral_key = $refdata;
            $contact->save();

            return true;
        }

        return false;
    }
}
