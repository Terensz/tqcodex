<?php

use Domain\User\Services\UserService;
use Domain\User\Services\AccessTokenService;

?>
<div>
    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <div class="max-w-xl">
            Sikeres feltöltés. Mehet egy újabb?
            <a href="{{ route('customer.project.compensation-item.bulk-upload', ['access_token' => AccessTokenService::getAccessToken(UserService::ROLE_TYPE_CUSTOMER)]) }}" class="text-blue-500 hover:text-blue-700 underline">
            {{ __('project.CompensationItemBulkUpload') }}
            </a>
        </div>
    </div>
</div>

