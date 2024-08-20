<?php

namespace Domain\Project\PipesSaveBulkComp;

use Closure;
use Domain\Project\Mails\PartnerInviteMail;
use Domain\Project\Services\PartnerInviteService;
use Domain\Shared\Helpers\CommunicationDispatcher;
use Domain\Shared\Helpers\StringHelper;

// use Illuminate\Support\Facades\Log;
// use Illuminate\Support\Facades\Mail;

// use Illuminate\Mail\SentMessage;

class SendInviteEmails
{
    public function handle($data, Closure $next)
    {
        $sharedRawSubject = $data['mail']['inviteEmailSubject'];
        $sharedRawBodyMain = $data['mail']['inviteEmailBody'];
        $senderAddress = $data['mail']['senderAddress'];
        $senderName = $data['mail']['senderName'];

        $emailDispatcher = new CommunicationDispatcher;
        $emailDispatcher->campaignReferenceCode = PartnerInviteMail::REFERENCE_CODE;
        $emailDispatcher->campaignTitleLangRef = PartnerInviteMail::TITLE_LANG_REF;
        $emailDispatcher->campaignIsListable = false;
        $emailDispatcher->campaignIsEditable = false;
        $emailDispatcher->campaignIsRedispatchable = false;
        $emailDispatcher->init();

        $dispatchesData = $data['collectedUniqueProperties']['partner_email'];
        foreach ($dispatchesData as $dispatchData) {
            // $subject = $data['inviteEmailSubject'];
            $bodyMain = StringHelper::replacePlaceholders($sharedRawBodyMain, [
                'organizationName' => $dispatchData['organizationName'],
                'partner_name' => $dispatchData['partner_name'],
                'partner_contact' => $dispatchData['partner_contact'],
                'contact.name' => $data['current_contact']->getNameAttribute(),
                'invite_register_link.href' => PartnerInviteService::getInvitedRegisterLink($dispatchData['partner_email'], $dispatchData['partner_name'], $dispatchData['partner_contact']),
            ]);

            $mailable = new PartnerInviteMail($sharedRawSubject, $bodyMain, [$dispatchData['partner_email'], $dispatchData['partner_contact']], [$senderAddress, $senderName]);
            $emailDispatcher->logAndSend($mailable);
        }
        $emailDispatcher->finish();

        return $next($data);
    }
}
