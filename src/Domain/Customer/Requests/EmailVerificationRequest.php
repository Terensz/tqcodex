<?php

namespace Domain\Customer\Requests;

use Domain\Customer\Models\Contact;
use Domain\User\Services\UserService;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class EmailVerificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $contact = $this->user(UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER));
        if (! $contact || ! $contact instanceof Contact || ! $contact->getContactProfile() || (! hash_equals((string) $contact->getContactProfile()->getKey(), (string) $this->route('id')))) {
            return false;
        }

        if (! hash_equals(sha1($contact->getContactProfile()->getEmailForVerification()), (string) $this->route('hash'))) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    /**
     * Fulfill the email verification request.
     *
     * @return void
     */
    public function fulfill()
    {
        $contact = $this->user(UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER));

        if ($contact instanceof Contact && $contact->getContactProfile()) {
            if (! $contact->getContactProfile()->hasVerifiedEmail()) {
                $contact->getContactProfile()->markEmailAsVerified();
                /** @phpstan-ignore-next-line */
                event(new Verified($contact->getContactProfile()));
            }
        }
    }

    /**
     * Configure the validator instance.
     *
     * @return \Illuminate\Validation\Validator
     */
    public function withValidator(Validator $validator)
    {
        return $validator;
    }
}
