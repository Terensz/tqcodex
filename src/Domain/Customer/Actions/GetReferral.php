<?php

declare(strict_types=1);

namespace Domain\Customer\Actions;

use Domain\Customer\Models\Contact;
use Illuminate\Support\Facades\Cookie;
use Trianity\Sqids\SqidsFactory;

final class GetReferral
{
    private SqidsFactory $sqids;

    private Contact $contact;

    public function __construct(SqidsFactory $sqids, Contact $contact)
    {
        $this->sqids = $sqids;
        $this->contact = $contact;
    }

    public function execute(): ?Contact
    {
        $cookie = Cookie::get('xref');

        $referredBy = $cookie ? $this->sqids->decode(strval($cookie))[0] : null;

        //get referral person
        if ($referredBy !== null && is_numeric($referredBy)) {
            if ($this->contact->where('id', $referredBy)->exists()) {
                $referral = $this->contact->find($referredBy);
                if ($referral instanceof Contact) {
                    return $referral;
                }
            }
        }

        return null;
    }
}
