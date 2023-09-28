<?php

namespace MintHCM\Api\Middlewares;

use MintHCM\Api\Routes\RouteManager;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;
use Slim\Routing\RouteContext;

abstract class Middleware
{
    protected $route_manager;

    public function __construct()
    {
        $this->route_manager = RouteManager::getInstance();
    }

    protected function getRouteData(Request $request)
    {
        $routes_data = $this->route_manager->getRoutes();

        $route_name = $this->getRouteName($request);
        if (!str_contains($route_name, '___')) {
            return $routes_data[$route_name];
        }

        $explode_name = explode('___', $route_name);
        $module_name = $explode_name[0] ?? false;
        $route_name = $explode_name[1] ?? false;
        if ($module_name && $route_name) {
            return $routes_data[$module_name][$route_name];
        }
        throw new HttpNotFoundException($request);
    }

    protected function getRouteName(Request $request)
    {
        $route = $this->getRoute($request);
        return $route->getName();
    }

    protected function getRoute(Request $request)
    {
        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();

        if (empty($route)) {
            throw new HttpNotFoundException($request);
        }
        return $route;
    }
}
