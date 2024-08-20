<?php

use Illuminate\Support\Facades\Route;
use Domain\User\Services\UserService;
use Domain\User\Services\AccessTokenService;
use Domain\Shared\Controllers\Base\BaseContentController;

$currentRouteName = Route::currentRouteName();

?>

{{-- Accounting --}}
<li>
    <p class="text-gray-900 group flex gap-x-3 block antialiased font-sans p-2 text-sm leading-6 font-bold capitalize text-primary">
    @include('components.icons.svg.credit-card')
    {{ __('project.Accounting') }}
    </p>
</li>

<li>
    <a href="{{ route('customer.project.compensation-item.list', ['access_token' => AccessTokenService::getAccessToken(UserService::ROLE_TYPE_CUSTOMER)]) }}" class="{{ $currentRouteName == 'customer.project.compensation-item.list' ? 'bg-gray-800' : '' }} text-gray-400 hover:text-white group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
    @include('components.icons.svg.copy')
    {{ __('project.CompensationItemList') }}
    </a>
</li>
<li>
    <a href="{{ route('customer.project.compensation-item.bulk-upload', ['access_token' => AccessTokenService::getAccessToken(UserService::ROLE_TYPE_CUSTOMER)]) }}" class="{{ $currentRouteName == 'customer.project.compensation-item.bulk-upload' ? 'bg-gray-800' : '' }} text-gray-400 hover:text-white group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
    @include('components.icons.svg.copy')
    {{ __('project.CompensationItemBulkUpload') }}
    </a>
</li>