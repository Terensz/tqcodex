<?php

namespace Domain\Testing\Services;

use Domain\Project\Services\PartnerInviteService;
use Domain\Shared\Helpers\CommunicationDispatcher;
use Domain\Shared\Helpers\StringHelper;
use Domain\Testing\Mails\TestMail;

class BulkMailSender
{
    public static function dispatch($data)
    {
        $sharedRawSubject = $data['inviteEmailSubject'];
        $sharedRawBodyMain = $data['inviteEmailBody'];
        $senderAddress = $data['senderAddress'];
        $senderName = $data['senderName'];

        $emailDispatcher = new CommunicationDispatcher;
        $emailDispatcher->campaignReferenceCode = TestMail::REFERENCE_CODE;
        $emailDispatcher->campaignTitleLangRef = TestMail::TITLE_LANG_REF;
        $emailDispatcher->campaignIsListable = false;
        $emailDispatcher->campaignIsEditable = false;
        $emailDispatcher->campaignIsRedispatchable = false;
        $emailDispatcher->init();

        $dispatchesData = $data['dispatches'];
        foreach ($dispatchesData as $dispatchData) {
            // $subject = $data['inviteEmailSubject'];
            $bodyMain = StringHelper::replacePlaceholders($sharedRawBodyMain, [
                'organizationName' => $dispatchData['organizationName'],
                'partner_name' => $dispatchData['partner_name'],
                'partner_contact' => $dispatchData['partner_contact'],
                'contact.name' => $data['current_contact']->getNameAttribute(),
                // 'invite_register_link.href' => PartnerInviteService::getInvitedRegisterLink($dispatchData['partner_email'], $dispatchData['partner_name'], $dispatchData['partner_contact']),
            ]);

            $mailable = new TestMail($sharedRawSubject, $bodyMain, [$dispatchData['partner_email'], $dispatchData['partner_contact']], [$senderAddress, $senderName]);
            // dump($emailDispatcher);
            // dump('dispatch@BulkMailSender');
            $emailDispatcher->logAndSend($mailable);
        }
        $emailDispatcher->finish();
    }
}
