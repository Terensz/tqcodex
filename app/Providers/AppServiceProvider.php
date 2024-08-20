<?php

namespace App\Providers;

use Domain\Customer\Models\OrgAddress;
use Domain\Customer\Observers\OrgAddressObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Paginator::defaultView('vendor.pagination.custom');
        OrgAddress::observe(OrgAddressObserver::class);
    }
}
