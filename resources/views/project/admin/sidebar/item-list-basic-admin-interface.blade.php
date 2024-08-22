<?php

use Illuminate\Support\Facades\Route;
use Domain\User\Services\UserService;
use Domain\User\Services\AccessTokenService;
use Domain\Shared\Controllers\Base\BaseContentController;

$currentRouteName = Route::currentRouteName();

?>

{{-- Users --}}
<li>
    <p class="text-gray-900 group flex gap-x-3 block antialiased font-sans p-2 text-sm leading-6 font-bold capitalize text-primary">
    @include('components.icons.svg.users')
    {{ __('admin.AdministratingUsers') }}
    </p>
</li>

<li>
    <a href="{{ route('admin.admin.list', ['access_token' => AccessTokenService::getAccessToken(UserService::ROLE_TYPE_ADMIN)]) }}" class="{{ $currentRouteName == 'admin.admin.list' ? 'bg-gray-800' : '' }} text-gray-400 hover:text-white group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
    @include('components.icons.svg.user-plus')
    {{ __('admin.AdminList') }}
    </a>
</li>
<li>
    <a href="{{ route('admin.customer.contact.list', ['access_token' => AccessTokenService::getAccessToken(UserService::ROLE_TYPE_ADMIN)]) }}" class="{{ $currentRouteName == 'admin.customer.contact.list' ? 'bg-gray-800' : '' }} text-gray-400 hover:text-white group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
    @include('components.icons.svg.user')
    {{ __('admin.CustomerList') }}
    </a>
</li>
<li>
    <a href="{{ route('admin.user.role.list', ['access_token' => AccessTokenService::getAccessToken(UserService::ROLE_TYPE_ADMIN)]) }}" class="{{ $currentRouteName == 'admin.user.role.list' ? 'bg-gray-800' : '' }} text-gray-400 hover:text-white group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
    @include('components.icons.svg.academic-cap')
    {{ __('admin.RoleList') }}
    </a>
</li>
<li>
    <a href="{{ route('admin.user.permission.list', ['access_token' => AccessTokenService::getAccessToken(UserService::ROLE_TYPE_ADMIN)]) }}" class="{{ $currentRouteName == 'admin.user.permission.list' ? 'bg-gray-800' : '' }} text-gray-400 hover:text-white group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
    @include('components.icons.svg.document-check')
    {{ __('admin.PermissionList') }}
    </a>
</li>

{{-- Settings --}}
<!-- <li>
    <p class="text-gray-900 group flex gap-x-3 block antialiased font-sans p-2 text-sm leading-6 font-bold capitalize text-primary">
    @include('components.icons.svg.cog-8-tooth')
    {{ __('admin.Settings') }}
    </p>
</li>

<li>
    <a href="{{ route('admin.system.setting.list', ['access_token' => AccessTokenService::getAccessToken(UserService::ROLE_TYPE_ADMIN)]) }}" class="{{ $currentRouteName == 'admin.system.setting.list' ? 'bg-gray-800' : '' }} text-gray-400 hover:text-white group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
    @include('components.icons.svg.cog')
    {{ __('admin.SystemSettings') }}
    </a>
</li> -->

{{-- Logs --}}
<li>
    <p class="text-gray-900 group flex gap-x-3 block antialiased font-sans p-2 text-sm leading-6 font-bold capitalize text-primary">
    @include('components.icons.svg.document-text')
    {{ __('admin.SystemLogs') }}
    </p>
</li>

<li>
    <a href="{{ route('admin.system.error-log.list', ['access_token' => AccessTokenService::getAccessToken(UserService::ROLE_TYPE_ADMIN)]) }}" class="{{ $currentRouteName == 'admin.system.error-log.list' ? 'bg-gray-800' : '' }} text-gray-400 hover:text-white group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
    @include('components.icons.svg.document-text')
    {{ __('admin.SystemErrorLogs') }}
    </a>
</li>
<li>
    <a href="{{ route('admin.system.visit-log.list', ['access_token' => AccessTokenService::getAccessToken(UserService::ROLE_TYPE_ADMIN)]) }}" class="{{ $currentRouteName == 'admin.system.visit-log.list' ? 'bg-gray-800' : '' }} text-gray-400 hover:text-white group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
    @include('components.icons.svg.document-text')
    {{ __('admin.VisitLogs') }}
    </a>
</li>
<li>
    <a href="{{ route('admin.user.user-activity-log.list', ['access_token' => AccessTokenService::getAccessToken(UserService::ROLE_TYPE_ADMIN)]) }}" class="{{ $currentRouteName == 'admin.user.user-activity-log.list' ? 'bg-gray-800' : '' }} text-gray-400 hover:text-white group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
    @include('components.icons.svg.document-text')
    {{ __('admin.UsersActivityLogs') }}
    </a>
</li>
<li>
    <a href="{{ route('admin.customer.contact-activity-log.list', ['access_token' => AccessTokenService::getAccessToken(UserService::ROLE_TYPE_ADMIN)]) }}" class="{{ $currentRouteName == 'admin.customer.contact-activity-log.list' ? 'bg-gray-800' : '' }} text-gray-400 hover:text-white group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
    @include('components.icons.svg.document-text')
    {{ __('admin.CustomersActivityLogs') }}
    </a>
</li>
