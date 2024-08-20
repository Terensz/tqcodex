<?php

namespace Domain\Shared\Helpers;

use Domain\Communication\Enums\CommunicationDispatchProcessStatus;
use Domain\Communication\Enums\CommunicationForm;
use Domain\Communication\Models\CommunicationCampaign;
use Domain\Communication\Models\CommunicationDispatch;
use Domain\Communication\Models\CommunicationDispatchProcess;
use Domain\Shared\Mails\Base\BaseMailable;
use Domain\User\Services\UserService;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class CommunicationDispatcher
{
    public ?CommunicationCampaign $communicationCampaign;

    public $campaignIsPublished = true;

    public $campaignIsListable = true;

    public $campaignIsEditable = true;

    public $campaignIsRedispatchable = true;

    public $campaignReferenceCode;

    public $campaignTitleLangRef;

    public ?CommunicationDispatchProcess $communicationDispatchProcess;

    public $dispatchProcessSenderContactId;

    public $dispatchProcessCommunicationForm = CommunicationForm::EMAIL->value;

    public function __construct()
    {
        $currentContact = UserService::getContact();
        $this->dispatchProcessSenderContactId = $currentContact ? $currentContact->id : null;
    }

    public function init()
    {
        if (! $this->campaignReferenceCode) {
            throw new Exception('campaignReferenceCode is missing.');
        }

        if (! $this->campaignTitleLangRef) {
            throw new Exception('campaignTitleLangRef is missing.');
        }

        $communicationCampaignProperties = [
            'reference_code' => $this->campaignReferenceCode,
            'title_lang_ref' => $this->campaignTitleLangRef,
            'is_published' => $this->campaignIsPublished,
            'is_listable' => $this->campaignIsListable,
            'is_editable' => $this->campaignIsEditable,
            'is_redispatchable' => $this->campaignIsRedispatchable,
        ];

        $communicationCampaign = new CommunicationCampaign($communicationCampaignProperties);
        $communicationCampaign->save();
        $communicationCampaign = $communicationCampaign->refresh();
        $this->communicationCampaign = $communicationCampaign;

        $communicationDispatchProcessProperties = [
            'communicationcampaign_id' => $communicationCampaign->id,
            'sender_contact_id' => $this->dispatchProcessSenderContactId,
            'communication_form' => $this->dispatchProcessCommunicationForm,
            'status' => CommunicationDispatchProcessStatus::IN_PROGRESS,
            'started_at' => Carbon::now(),
        ];

        $communicationDispatchProcess = new CommunicationDispatchProcess($communicationDispatchProcessProperties);
        $communicationDispatchProcess->save();
        $communicationDispatchProcess = $communicationDispatchProcess->refresh();
        $this->communicationDispatchProcess = $communicationDispatchProcess;
    }

    /**
     * This method serves only test purposes!!!
     */
    public function log(BaseMailable $mailable)
    {
        $this->createDispatchRecord($mailable, $this->extractSenderAndRecipientData($mailable));
    }

    private function extractSenderAndRecipientData(BaseMailable $mailable)
    {
        $senderAddress = $mailable->senderAddress;
        $senderName = $mailable->senderName;
        $recipient = $mailable->recipient;
        $recipientAddress = null;
        $recipientName = null;
        if (count($recipient) === 1) {
            $recipientAddress = $recipient[0];
        } elseif (count($recipient) === 2) {
            $recipientAddress = $recipient[0];
            $recipientName = $recipient[1];
        } else {
            throw new Exception('Invalid recipient.');
        }

        return [
            'senderAddress' => $senderAddress,
            'senderName' => $senderName,
            'recipientAddress' => $recipientAddress,
            'recipientName' => $recipientName,
        ];
    }

    public function logAndSend(BaseMailable $mailable)
    {
        $senderAndRecipientData = $this->extractSenderAndRecipientData($mailable);

        Mail::to($senderAndRecipientData['recipientAddress'], $senderAndRecipientData['recipientName'])->send($mailable);

        $this->createDispatchRecord($mailable, $senderAndRecipientData);
    }

    public function createDispatchRecord(BaseMailable $mailable, $senderAndRecipientData)
    {
        if (! $this->communicationCampaign || ! $this->communicationDispatchProcess) {
            throw new \Exception('communicationCampaign or communicationDispatchProcess is missing. You should run init() before createDispatch() !');
        }

        $communicationDispatchProperties = [
            'communicationdispatchprocess_id' => $this->communicationDispatchProcess->id,
            'subject' => $mailable->subject,
            'body' => $mailable->body,
            'sender_address' => $senderAndRecipientData['senderAddress'],
            'sender_name' => $senderAndRecipientData['senderName'],
            'recipient_contact_id' => null,
            'recipient_address' => $senderAndRecipientData['recipientAddress'],
            'recipient_name' => $senderAndRecipientData['recipientName'],
            'sent_at' => Carbon::now(),
        ];

        $communicationDispatch = new CommunicationDispatch($communicationDispatchProperties);
        $communicationDispatch->save();
        $communicationDispatch = $communicationDispatch->refresh();
    }

    public function finish()
    {
        $this->communicationDispatchProcess->status = CommunicationDispatchProcessStatus::FINISHED;
        $this->communicationDispatchProcess->finished_at = Carbon::now();
        $this->communicationDispatchProcess->save();
    }
}
