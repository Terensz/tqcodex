<?php

use Illuminate\Support\Facades\Route;
use Domain\User\Services\UserService;
use Domain\User\Services\AccessTokenService;
use Domain\Shared\Controllers\Base\BaseContentController;

$currentRouteName = Route::currentRouteName();
?>

{{-- Control interfaces --}}
@include('project/customer/sidebar/item-list-control-interfaces')

{{-- User settings --}}
@if($contentBranch == BaseContentController::CONTENT_BRANCH_BASIC_CUSTOMER_INTERFACE)
    @include('project/customer/sidebar/item-list-basic-customer-interface')
@endif

{{-- Customer organizations interface --}}
@if($contentBranch == BaseContentController::CONTENT_BRANCH_ORGANIZATIONS_CUSTOMER_INTERFACE)
    @include('project/customer/sidebar/item-list-organizations-customer-interface')
@endif

{{-- Customer project interface --}}
@if($contentBranch == BaseContentController::CONTENT_BRANCH_PROJECT_CUSTOMER_INTERFACE)
    @include('project/customer/sidebar/item-list-project-customer-interface')
@endif

{{-- Customer communication interface --}}
@if($contentBranch == BaseContentController::CONTENT_BRANCH_COMMUNICATION_CUSTOMER_INTERFACE)
    @include('project/customer/sidebar/item-list-communication-customer-interface')
@endif