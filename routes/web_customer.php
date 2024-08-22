<?php

use Illuminate\Support\Facades\Route;

Route::match(['get'], 'register', [\Domain\Customer\Controllers\CustomerRegistrationController::class, 'register'])
    ->name('customer.contact.register');

Route::match(['get'], '/invited-register/partnerEmail/{partnerEmail}/partnerName/{partnerName}/partnerContact/{partnerContact}', [\Domain\Customer\Controllers\CustomerRegistrationController::class, 'invitedRegister'])
    ->name('customer.contact.invited-register');
