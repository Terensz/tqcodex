<?php

namespace App\Http\Middleware;

use Closure;
use Domain\Shared\Events\RouteCalled;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class LogRoute extends Middleware
{
    public function handle($request, Closure $next, ...$permissions)
    {
        RouteCalled::dispatch();

        // dump($next($request));exit;

        return $next($request);
    }
}
