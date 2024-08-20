<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

// use Domain\User\Services\SimpleHasher;
use Illuminate\Container\Container;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Hashing\HashManager;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Auth::provider('customers_provider_driver', function ($app, array $config) {
            return new CustomerUserProvider(new HashManager(Container::getInstance()), $config['model']);
        });
    }
}
