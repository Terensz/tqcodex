<?php

// use Illuminate\Support\Facades\Route as RouteHelper;

use Domain\Shared\Helpers\RouteHelper;
use Illuminate\Routing\Route;

/*
Standalone run:
php artisan test tests/Feature/Route/UngroupedRouteTest.php
*/

it('checks uncategorized routes.', function () {
    $routing = RouteHelper::getRouting();
    $ungroupedFound = [];
    foreach ($routing as $routeName => $routeParams) {
        $routeString = $routeName;
        if (empty($routeParams['contentGroup']) && in_array($routeParams['location'], ['Domain', 'App'])) {
            $ungroupedFound[] = $routeString;
        }
    }

    $this->assertEquals([], $ungroupedFound);
});
