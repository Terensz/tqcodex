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
    <a href="{{ route('customer.profile.edit', ['access_token' => AccessTokenService::getAccessToken(UserService::ROLE_TYPE_CUSTOMER)]) }}" class="{{ $contentBranch == BaseContentController::CONTENT_BRANCH_BASIC_CUSTOMER_INTERFACE ? 'bg-gray-800' : '' }} text-gray-400 hover:text-white group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
    @include('components.icons.svg.folder')
    {{ __('user.UserSettings') }}
    </a>
</li>
<li>
    <a href="{{ route('customer.organization.list', ['access_token' => AccessTokenService::getAccessToken(UserService::ROLE_TYPE_CUSTOMER)]) }}" class="{{ $contentBranch == BaseContentController::CONTENT_BRANCH_ORGANIZATIONS_CUSTOMER_INTERFACE ? 'bg-gray-800' : '' }} text-gray-400 hover:text-white group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
    @include('components.icons.svg.folder')
    {{ __('customer.OrganizationsInterface') }}
    </a>
</li>
<li>
    <a href="{{ route('customer.project.compensation-item.list', ['access_token' => AccessTokenService::getAccessToken(UserService::ROLE_TYPE_CUSTOMER)]) }}" class="{{ $contentBranch == BaseContentController::CONTENT_BRANCH_PROJECT_CUSTOMER_INTERFACE ? 'bg-gray-800' : '' }} text-gray-400 hover:text-white group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
    @include('components.icons.svg.folder')
    {{ __('project.ProjectCustomerInterface') }}
    </a>
</li>
<li>
    <a href="{{ route('customer.communication.email-dispatch-process.list', ['access_token' => AccessTokenService::getAccessToken(UserService::ROLE_TYPE_CUSTOMER)]) }}" class="{{ $contentBranch == BaseContentController::CONTENT_BRANCH_COMMUNICATION_CUSTOMER_INTERFACE ? 'bg-gray-800' : '' }} text-gray-400 hover:text-white group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
    @include('components.icons.svg.folder')
    {{ __('communication.CustomerCommunicationInterface') }}
    </a>
</li>