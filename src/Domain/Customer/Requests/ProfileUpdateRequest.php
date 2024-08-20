<?php

namespace Domain\Customer\Requests;

use Domain\Customer\Models\Contact;
use Domain\Customer\Rules\UniqueCustomerEmail;
use Domain\User\Services\UserService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        // $rule = Rule::unique(Contact::class);

        // $user = UserService::getUser(UserService::ROLE_TYPE_CUSTOMER);

        // if ($user && $user instanceof Contact) {
        //     $rule = $rule->ignore($user->id);
        // }

        $rules = [
            'lastname' => ['required', 'string', 'max:255'],
            'firstname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
        ];

        $contactProfile = UserService::getContactProfile();
        if ($contactProfile) {
            $rules['email'][] = new UniqueCustomerEmail($contactProfile, $this->email);
        }

        return $rules;

        // return [
        //     'lastname' => ['required', 'string', 'max:255'],
        //     'firstname' => ['required', 'string', 'max:255'],
        //     'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
        // ];
    }

    /**
     * Terence: ez volt az eredeti
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    // public function rules_OLD(): array
    // {
    //     return [
    //         'name' => ['required', 'string', 'max:255'],
    //         'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
    //     ];
    // }
}
