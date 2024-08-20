<?php

use Domain\User\Services\UserService;
use Domain\User\Services\AccessTokenService;

?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('user.ProfileInformation') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('user.ProfileInformationLabel') }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('customer.verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('customer.profile.update', ['access_token' => AccessTokenService::getAccessToken(UserService::ROLE_TYPE_CUSTOMER)]) }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="lastname" :value="__('user.Lastname')" />
            <x-text-input id="lastname" name="lastname" type="text" class="mt-1 block w-full" :value="old('lastname', $contactProfile->lastname)" autofocus autocomplete="lastname" />
            <x-input-error class="mt-2" :messages="$errors->get('lastname')" />
        </div>

        <div>
            <x-input-label for="firstname" :value="__('user.Firstname')" />
            <x-text-input id="firstname" name="firstname" type="text" class="mt-1 block w-full" :value="old('firstname', $contactProfile->firstname)" autofocus autocomplete="firstname" />
            <x-input-error class="mt-2" :messages="$errors->get('firstname')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('user.Email')" />
            <x-text-input id="email" name="email" class="mt-1 block w-full" :value="old('email', $contactProfile->email)" autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($contactProfile instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $contactProfile->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('user.EmailUnverifiedMessage') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            {{ __('user.EmailResendClickMessage') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                        {{ __('user.EmailResendMessage') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('shared.Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('shared.Saved') }}</p>
            @endif
        </div>
    </form>
</section>
