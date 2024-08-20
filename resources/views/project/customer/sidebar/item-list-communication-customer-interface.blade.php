<?php

use Illuminate\Support\Facades\Route;
use Domain\User\Services\UserService;
use Domain\User\Services\AccessTokenService;
use Domain\Shared\Controllers\Base\BaseContentController;

$currentRouteName = Route::currentRouteName();

?>

<li>
    <p class="text-gray-900 group flex gap-x-3 block antialiased font-sans p-2 text-sm leading-6 font-bold capitalize text-primary">
    @include('components.icons.svg.phone')
    {{ __('communication.Communication') }}
    </p>
</li>

<li>
    <a href="{{ route('customer.communication.email-dispatch-process.list', ['access_token' => AccessTokenService::getAccessToken(UserService::ROLE_TYPE_CUSTOMER)]) }}" class="{{ $currentRouteName == 'customer.communication.email-dispatch-process.list' ? 'bg-gray-800' : '' }} text-gray-400 hover:text-white group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
    @include('components.icons.svg.copy')
    {{ __('communication.EmailDispatchProcesses') }}
    </a>
</li>
<li>
    <a href="{{ route('customer.communication.email-dispatch.list', [
        'access_token' => AccessTokenService::getAccessToken(UserService::ROLE_TYPE_CUSTOMER), 
        'communicationDispatchProcess' => null
    ]) }}" class="{{ $currentRouteName == 'customer.communication.email-dispatch.list' ? 'bg-gray-800' : '' }} text-gray-400 hover:text-white group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
    @include('components.icons.svg.copy')
    {{ __('communication.EmailDispatches') }}
    </a>
</li>
