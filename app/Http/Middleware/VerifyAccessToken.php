<?php

namespace App\Http\Middleware;

use Closure;
use Domain\User\Services\AccessTokenService;
use Domain\User\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class VerifyAccessToken
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $roleType)
    {
        $accessToken = $request->route('access_token');

        // dump($next($request));exit;

        if (! $this->isValidAccessToken($accessToken, $roleType)) {
            return Redirect::to(UserService::getLogoutRoute($roleType));
        }

        return $next($request);
    }

    /**
     * Verify access token
     *
     * @param  string|null  $accessToken
     * @return bool
     */
    private function isValidAccessToken($accessToken, $roleType)
    {
        return $accessToken === AccessTokenService::getAccessToken($roleType);
    }
}
