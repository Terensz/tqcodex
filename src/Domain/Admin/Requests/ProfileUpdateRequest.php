<?php

namespace Domain\Admin\Requests;

use Domain\Admin\Models\Admin;
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
        $rule = Rule::unique(Admin::class);
        $user = UserService::getUser(UserService::ROLE_TYPE_ADMIN);

        if ($user && $user instanceof Admin) {
            $rule = $rule->ignore($user->id);
        }

        return [
            'lastname' => ['required', 'string', 'max:255'],
            'firstname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', $rule],
        ];
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
