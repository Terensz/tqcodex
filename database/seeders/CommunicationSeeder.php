<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Domain\Customer\Models\Contact;
use Domain\Customer\Models\ContactProfile;
use Domain\Project\Mails\PartnerInviteMail;
use Domain\Shared\Helpers\CommunicationDispatcher;
use Illuminate\Database\Seeder;

class CommunicationSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $contactProfile = ContactProfile::where(['email' => 'terencecleric@gmail.com'])->first();
        $contact = $contactProfile->getContact();

        for ($looper = 0; $looper < 30; $looper++) {
            self::send($contact, $looper);
        }
    }

    public static function send(Contact $senderContact, $looper = ''): CommunicationDispatcher
    {
        $senderContactProfile = $senderContact->getContactProfile();
        $emailDispatcher = new CommunicationDispatcher;
        $emailDispatcher->campaignReferenceCode = PartnerInviteMail::REFERENCE_CODE;
        $emailDispatcher->campaignTitleLangRef = PartnerInviteMail::TITLE_LANG_REF;
        $emailDispatcher->campaignIsListable = false;
        $emailDispatcher->campaignIsEditable = false;
        $emailDispatcher->campaignIsRedispatchable = false;
        $emailDispatcher->dispatchProcessSenderContactId = $senderContact->id;
        $emailDispatcher->init();
        $subject = 'Test subject '.$looper;
        $bodyMain = 'Test body main '.$looper;
        $recipientEmail = 'terencecleric@gmail.com';
        $recipientName = 'Papp Ferenc';
        $mailable = new PartnerInviteMail($subject, $bodyMain, [$recipientEmail, $recipientName], [$senderContactProfile->email, $senderContactProfile->getName()]);
        $emailDispatcher->log($mailable);
        $emailDispatcher->finish();

        return $emailDispatcher;
    }
}
