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
    <a href="{{ route('admin.finance.cashflow-data.list', ['access_token' => AccessTokenService::getAccessToken(UserService::ROLE_TYPE_ADMIN)]) }}" class="{{ $currentRouteName == 'admin.finance.cashflow-data.list' ? 'bg-gray-800' : '' }} text-gray-400 hover:text-white group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
    @include('components.icons.svg.copy')
    {{ __('project.CashflowData') }}
    </a>
</li>
<li>
    <a href="{{ route('admin.finance.account-settlement-log.list', ['access_token' => AccessTokenService::getAccessToken(UserService::ROLE_TYPE_ADMIN)]) }}" class="{{ $currentRouteName == 'admin.finance.account-settlement-log.list' ? 'bg-gray-800' : '' }} text-gray-400 hover:text-white group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
    @include('components.icons.svg.copy')
    {{ __('project.AccountSettlementLog') }}
    </a>
</li>
<li>
    <a href="{{ route('admin.project.customer-referral-system.index', ['access_token' => AccessTokenService::getAccessToken(UserService::ROLE_TYPE_ADMIN)]) }}" class="{{ $currentRouteName == 'admin.project.customer-referral-system.index' ? 'bg-gray-800' : '' }} text-gray-400 hover:text-white group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
    @include('components.icons.svg.copy')
    {{ __('project.CustomerReferralSystem') }}
    </a>
</li>
<li>
    <a href="{{ route('admin.project.service-prices.index', ['access_token' => AccessTokenService::getAccessToken(UserService::ROLE_TYPE_ADMIN)]) }}" class="{{ $currentRouteName == 'admin.project.service-prices.index' ? 'bg-gray-800' : '' }} text-gray-400 hover:text-white group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
    @include('components.icons.svg.copy')
    {{ __('project.ServicePrices') }}
    </a>
</li>
