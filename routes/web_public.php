<?php

use Illuminate\Support\Facades\Route;


Route::match(['get'], '/', [\Domain\Project\Controllers\HomepageController::class, 'index'])
    ->name('project.homepage');