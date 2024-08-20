<?php

namespace Domain\Project\Livewire;

use Domain\Customer\Models\Contact;
use Domain\Customer\Models\ContactProfile;
use Domain\Customer\Models\Organization;
use Domain\Project\Livewire\Forms\ContactRegisterForm;
use Domain\Shared\Livewire\Base\BaseEditComponent;
use Domain\User\Services\AccessTokenService;
use Domain\User\Services\UserService;
use Illuminate\Database\QueryException;

final class ContactRegister extends BaseEditComponent
{
    public ContactRegisterForm $form;

    public $invitedRegister;

    // public $partnerEmail;

    // public $partnerName;

    // public $partnerContact;

    public static function getComponentName(): string
    {
        return 'contact-register';
    }

    public function getEditRouteComponents(): array
    {
        return [
            'name' => 'customer.contact.register',
            'paramArray' => [],
        ];
    }

    public function boot()
    {
        //
        // 'invitedRegister' => $invitedRegister,
        // 'partnerEmail' => $partnerEmail,
        // 'partnerName' => $partnerName,
        // 'partnerContact' => $partnerContact,
    }

    public function mount($invitedRegister, $partnerEmail, $partnerName, $partnerContact)
    {
        // dump($invitedRegister, $partnerEmail, $partnerName, $partnerContact);exit;
        // $contactProfile = new ContactProfile();
        // $contact = new Contact();
        // $contact->setContactProfileAttribute(new ContactProfile());

        // dump($contact->getContactProfileAttribute()); exit;
        // dump($contact);
        // dump($contact->getContactProfile());exit;
        $this->form->setContact(new Contact);
        $this->form->setContactProfile(new ContactProfile);
        $this->form->setOrganization(new Organization);
        $this->invitedRegister = $invitedRegister;
        // $this->partnerEmail = $partnerEmail;
        // $this->partnerName = $partnerName;
        // $this->partnerContact = $partnerContact;
        $this->form->organization_email = $partnerEmail;
        $this->form->organization_name = $partnerName;
        // $this->form->partner_contact = $partnerContact;

        // dump($this->form->contact);
        // dump($this->form->contact->getContactProfileAttribute());exit;
        $this->actionType = BaseEditComponent::ACTION_TYPE_NEW;
    }

    public function getEntityObject()
    {
        return $this->form->contact;
    }

    public function refreshView()
    {
        return $this->render();
    }

    public function save()
    {
        try {
            if ($this->getForm()) {
                $this->getForm()->store();
                $this->getForm()->formSaved = true;

                return $this->render();
            }

            /**
             * This must not be \Exception, because than the validation system collapses.
             */
        } catch (QueryException $e) {
            $this->dispatch('save-failed');
        }
    }

    public function render()
    {
        if ($this->form->formSaved) {
            $this->form->contact->refresh();
            UserService::quietLogin(UserService::ROLE_TYPE_CUSTOMER, $this->form->contact);

            $contactAccessToken = AccessTokenService::isAccessToken(UserService::ROLE_TYPE_CUSTOMER) ? AccessTokenService::getAccessToken(UserService::ROLE_TYPE_CUSTOMER) : null;
            if (! $contactAccessToken) {
                $contactAccessToken = AccessTokenService::createAccessToken();
                AccessTokenService::setAccessToken(UserService::ROLE_TYPE_CUSTOMER, $contactAccessToken);
            }

            $this->form->contact->getContactProfile()->sendEmailVerificationNotification();

            return redirect(route('customer.verification.notice'));
            // return redirect(route('customer.verification.notice', [
            //     'access_token' => $contactAccessToken,
            // ]));
            // return view('customer.contact.register-successful');
        }

        $viewParams = [
            'formData' => ContactRegisterForm::getFormData(),
            'pageTitle' => $this->form->contact->id ? __('shared.EditItem', ['item' => __('customer.Contact')]) : __('shared.CreateNewItem', ['item' => __('customer.Contact')]),
            'pageShortDescription' => __('project.EditCompensationItemDescription'),
        ];

        return view('customer.contact.register-form', $viewParams);
    }
}
