<?php

use Illuminate\Support\Facades\Route;
use Domain\User\Services\UserService;
use Domain\User\Services\AccessTokenService;

$currentRouteName = Route::currentRouteName();

?>
<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('user.UploadProfilePhoto') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('user.UploadProfilePhotoDescription') }}
        </p>
    </header>

    <!-- <input type="file" class="block w-full text-sm text-slate-500
                file:mr-4 file:py-2 file:px-4
                file:rounded-full file:border-0
                file:text-sm file:font-semibold
                file:bg-violet-50 file:text-violet-700
                hover:file:bg-violet-100
            "/> -->

    <form action="{{ route('customer.profile.image.upload', ['access_token' => AccessTokenService::getAccessToken(UserService::ROLE_TYPE_CUSTOMER)]) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <label class="block">
            <span class="sr-only">{{ __('user.UploadProfilePhotoBrowse') }}</span>
            <!-- <input type="file" class="" name="profile_image_0"> -->
            <input type="file" class="hidden" name="profile_image" id="profile_image" accept="image/*" x-on:change.prevent="$event.target.form.submit();" />
            <span class="inline-flex items-center 
                px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent 
                rounded-md font-semibold text-xs text-white dark:text-gray-800 
                uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white 
                focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 
                dark:active:bg-gray-300 focus:outline-none focus:ring-2 
                focus:ring-indigo-500 focus:ring-offset-2 
                dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 cursor-pointer"
            >{{ __('user.UploadProfilePhotoBrowse') }}</span>
        </label>

    </form>
    @if($contactProfile->profile_image)
    <div class="shrink-0">
        <img class="h-16 w-16 object-cover rounded-full" src="{{ route('customer.profile.image.show', ['access_token' => AccessTokenService::getAccessToken(UserService::ROLE_TYPE_CUSTOMER)]) }}?random={{ random_int(3400000000, 9999999999999) }}" alt="{{ __('user.CurrentProfilePhoto') }}" />
    </div>
    @endif
</section>