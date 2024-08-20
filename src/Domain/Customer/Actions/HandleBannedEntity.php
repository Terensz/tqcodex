<?php

declare(strict_types=1);

namespace Domain\Customer\Actions;

use Domain\Customer\Models\Contact;
use Domain\Customer\Models\Organization;
use Domain\Shared\Mails\ProhibitedApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

final class HandleBannedEntity
{
    public function execute(Request $request): bool
    {
        if (Contact::isBanned(strval($request->email))) {
            return $this->handleBannedEntity(strval($request->email), $request);
        }

        $orgIdentification = $request->taxpayer_id ??
                                $request->eutaxid ??
                                $request->taxid ??
                                $request->email;
        if (Organization::isBanned(strval($orgIdentification))) {
            return $this->handleBannedEntity(strval($orgIdentification), $request);
        }

        return false;
    }

    protected function handleBannedEntity(string $identifier, Request $request): bool
    {
        $data = [];
        $data['identifier'] = $identifier;
        $data['ip'] = strval($request->ip());
        $data['tr_date'] = $request->tr_code.': '.$request->tr_date;
        $data['tr_code'] = $request->tr_code ?? '';
        $data['landing_page'] = $request->landing_page ?? '';
        Mail::to(config('crm.email.webmaster'))
            ->queue(new ProhibitedApplication($data));

        return true;
    }
}
