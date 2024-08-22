<?php

use Illuminate\Support\Facades\Route;
use Domain\User\Services\UserService;
use Domain\User\Services\AccessTokenService;
use Domain\Shared\Controllers\Base\BaseContentController;

$currentRouteName = Route::currentRouteName();

?>

{{-- Control interfaces --}}
<li>
    <p class="text-gray-900 group flex gap-x-3 block antialiased font-sans p-2 text-sm leading-6 font-bold capitalize text-primary">
    @include('components.icons.svg.folder')
    {{ __('user.ControlInterfaces') }}
    </p>
</li>

<li>
    <a href="{{ route('admin.dashboard', ['access_token' => AccessTokenService::getAccessToken(UserService::ROLE_TYPE_ADMIN)]) }}" class="{{ $contentBranch == BaseContentController::CONTENT_BRANCH_BASIC_ADMIN_INTERFACE ? 'bg-gray-800' : '' }} text-gray-400 hover:text-white group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
    @include('components.icons.svg.folder')
    {{ __('admin.BasicAdminInterface') }}
    </a>
</li>
@if(config('project.webshop_active'))
<li>
    <a href="{{ route('admin.webshop.dashboard', ['access_token' => AccessTokenService::getAccessToken(UserService::ROLE_TYPE_ADMIN)]) }}" class="{{ $contentBranch == BaseContentController::CONTENT_BRANCH_WEBSHOP_ADMIN_INTERFACE ? 'bg-gray-800' : '' }} text-gray-400 hover:text-white group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
    @include('components.icons.svg.folder')
    {{ __('admin.WebshopAdminInterface') }}
    </a>
</li>
@endif