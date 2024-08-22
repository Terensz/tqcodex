<?php

use Illuminate\Support\Facades\Route;
use Domain\User\Services\UserService;
use Domain\User\Services\AccessTokenService;
use Domain\Shared\Controllers\Base\BaseContentController;

$currentRouteName = Route::currentRouteName();

?>

{{-- Control interfaces --}}
@include('project/admin/sidebar/item-list-control-interfaces')

{{-- Basic admin interface --}}
@if($contentBranch == BaseContentController::CONTENT_BRANCH_BASIC_ADMIN_INTERFACE)
    @include('project/admin/sidebar/item-list-basic-admin-interface')
@endif

{{-- Project admin interface --}}
@if($contentBranch == BaseContentController::CONTENT_BRANCH_PROJECT_ADMIN_INTERFACE)
    @include('project/admin/sidebar/item-list-project-admin-interface')
@endif
