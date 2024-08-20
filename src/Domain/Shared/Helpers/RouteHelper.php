<?php

namespace Domain\Shared\Helpers;

use Illuminate\Support\Facades\Route;

class RouteHelper
{
    private static $cachedRouting;

    private const FILTER_ALL = 'FilterAll';

    public static function getDomainPath()
    {
        return app()->basePath('src/Domain');
    }

    public static function getCurrentRouteObject()
    {
        return Route::getCurrentRoute();
    }

    public static function getCurrentRouteName()
    {
        return Route::getCurrentRoute()->getName();
    }

    public static function getCurrentRouteUrl()
    {
        return Route::getCurrentRoute()->uri;
    }

    public static function getRouting($filterToContectGroup = self::FILTER_ALL)
    {
        if (isset(self::$cachedRouting[$filterToContectGroup])) {
            return self::$cachedRouting[$filterToContectGroup];
        }

        $routing = [];
        $routeCollection = \Illuminate\Support\Facades\Route::getRoutes();
        if ($routeCollection instanceof \Illuminate\Routing\RouteCollection) {
            foreach ($routeCollection as $route) {
                if ($route instanceof \Illuminate\Routing\Route) {
                    $routeName = $route->getName();
                    $routeAction = $route->getAction();
                    $contentGroup = null;
                    $contentBranch = null;
                    if (isset($routeAction['controller'])) {
                        // if ($routeName == 'customer.password.request') {
                        //     dump($route->getAction());exit;
                        // }
                        $controllerParts = explode('@', $routeAction['controller']);
                        $controllerPathParts = explode('\\', $controllerParts[0]);
                        $controllerClass = '\\'.ltrim($controllerParts[0], '\\');
                        $controllerMethod = isset($controllerParts[1]) ? $controllerParts[1] : null;

                        $permissionMiddleware = null;
                        if (isset($routeAction['middleware'])) {
                            foreach ($routeAction['middleware'] as $middleware) {
                                $permissionPos = PHPHelper::getStringPosition('permission:', $middleware);
                                if ($permissionPos === 0) {
                                    $permissionMiddlewareParts = explode(',', ltrim($middleware, 'permission:'));
                                    $permissionMiddleware = [
                                        'permission' => $permissionMiddlewareParts[0],
                                        'guard' => $permissionMiddlewareParts[1],
                                    ];
                                }
                            }
                        }

                        if (method_exists($controllerClass, 'getContentBranch')) {
                            $contentGroup = $controllerClass::getContentGroup();
                            $contentBranch = $controllerClass::getContentBranch();
                        }

                        if ($filterToContectGroup == self::FILTER_ALL || $filterToContectGroup == $contentGroup) {
                            $routing[! empty($routeName) ? $routeName : 'missing_name_'.count($routing)] = [
                                'location' => $controllerPathParts[0],
                                'controllerClass' => $controllerClass,
                                'controllerMethod' => $controllerMethod,
                                'permissionMiddleware' => $permissionMiddleware,
                                'contentGroup' => $contentGroup,
                                'contentBranch' => $contentBranch,
                                'name' => $routeName,
                                'url' => $route->uri,
                                'action' => $routeAction,
                            ];
                        }
                    }
                }
            }
        }

        self::$cachedRouting[$filterToContectGroup] = $routing;

        return $routing;
    }
}
