<?php

namespace Domain\System\Listeners;

use Domain\Shared\Events\RouteCalled;
use Domain\Shared\Helpers\PHPHelper;
use Domain\Shared\Helpers\RouteHelper;
use Domain\Shared\Helpers\StringHelper;
use Domain\System\Models\VisitLog;
use Domain\User\Events\ActivityLogRequested;
use Domain\User\Services\UserService;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

class SaveVisitLog
{
    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(?RouteCalled $event = null): void
    {
        $formattedUrl = self::getFormattedUrl();

        /**
         * Checking each and every guards if they authenticated a user.
         */
        $user_id = null;
        $contact_id = null;
        foreach (UserService::getGuardedUsers() as $roleType => $userModel) {

            if ($userModel && $roleType === UserService::ROLE_TYPE_ADMIN) {
                $user_id = $userModel->id;
            }
            if ($userModel && $roleType === UserService::ROLE_TYPE_CUSTOMER) {
                $contact_id = $userModel->id;
            }
        }

        $day = PHPHelper::date('Y-m-d');

        $visitLog = VisitLog::where([
            'day' => $day,
            'ip_address' => Request::ip(),
            'host' => Request::host(),
            'url' => $formattedUrl,
            'user_agent' => Request::header('User-Agent'),
        ])->first();

        if ($visitLog && $visitLog instanceof VisitLog) {
            // dump($visitLog);exit;
            $visitLog->count_of_visits += 1;
        } else {
            $visitLog = new VisitLog;
            $visitLog->day = $day;
            $visitLog->ip_address = Request::ip();
            $visitLog->host = Request::host();
            $visitLog->url = $formattedUrl;
            $visitLog->count_of_visits = 1;
            $visitLog->user_agent = Request::header('User-Agent');
            // $visitLog->route_name = RouteHelper::getCurrentRouteName();
        }

        $visitLog->user_id = $user_id;
        $visitLog->contact_id = $contact_id;
        $visitLog->save();
    }

    public static function getFormattedUrl()
    {
        $url = '';
        $route = \Illuminate\Support\Facades\Request::route();
        if ($route instanceof \Illuminate\Routing\Route) {
            $url = $route->uri;
            $routeParameters = $route->parameters;
            $url = StringHelper::replacePlaceholders($url, $routeParameters, '{', '}', 10);
        }

        return $url;
    }

    // public function dispatchActivityLogRequested($roleType, $url, $userModel = null)
    // {
    //     ActivityLogRequested::dispatch(
    //         $roleType,
    //         $userModel,
    //         CreateActivityLog::ACTION_PAGE_VISIT,
    //         null,
    //         $url,
    //         null
    //     );
    // }
}
