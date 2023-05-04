<?php

namespace MintHCM\Api\Routes;

use MintHCM\Utils\CustomLoader;

class RouteManager
{
    protected static $_instance;

    protected $app;

    protected $routes = array();
    protected $modules_routes = array();

    protected $routes_locations = [
        'app/Routes/routes/',
        'custom/app/Routes/routes/',
    ];

    protected $modules_locations = [
        'app/Routes/modules/',
        'custom/app/Routes/modules/',
        'modules/{module_name}/api/routes/',
        'custom/{module_name}/api/routes/',
    ];

    public function __construct()
    {
        global $app;

        $this->app = $app;
        $this->setRoutes();
    }

    public static function getInstance()
    {
        if (!is_object(self::$_instance)) {
            self::$_instance = CustomLoader::getObject(RouteManager::class);
        }
        return self::$_instance;
    }

    public function execute()
    {
        $this->buildRoutes($this->routes);
        foreach ($this->modules_routes as $module => $routes) {
            $this->buildModulesRoutes($module, $routes);
        }
    }

    public function getRoutes()
    {
        return array_merge($this->routes, $this->modules_routes);
    }

    protected function buildModulesRoutes($module, &$routes)
    {
        foreach ($routes as $route_name => $route_data) {
            if (!isset($route_data['path'])) {
                continue;
            }
            $route_data['path'] = '/' . $module . $route_data['path'];
            $route_name = $module . '___' . $route_name;
            $this->buildRoute($route_data, $route_name);
        }
    }

    protected function buildRoutes(&$routes)
    {
        foreach ($routes as $route_name => $route_data) {
            $this->buildRoute($route_data, $route_name);
        }
    }

    protected function buildRoute(&$route_data, $route_name)
    {
        $route = $this->addRoute($route_data);
        if (!$route) {
            return null;
        }

        $route->setName($route_name);
    }

    protected function addRoute($route)
    {
        if ($this->shouldSkipRoute($route)) {
            return false;
        }

        $method = $this->getMethod($route);
        if (!$method) {
            return false;
        }

        $route_function = empty($route['function']) ? $route['class'] : [$route['class'], $route['function']];
        return $this->app->map($method, $route['path'], $route_function);
    }

    protected function shouldSkipRoute($route)
    {
        return empty($route['method'])
        || empty($route['path'])
        || empty($route['class'])
        || !class_exists($route['class'])
            || (
            !method_exists($route['class'], '__invoke')
            && (
                empty($route['function'])
                || !method_exists($route['class'], $route['function'])
            )
        )
        ;
    }

    protected function getMethod($route)
    {
        if (empty($route['method'])) {
            return false;
        }

        if (is_string($route['method'])) {
            return array(strtoupper($route['method']));
        }
        if (is_array($route['method'])) {
            return array_map('strtoupper', $route['method']);
        }
        return false;
    }

    protected function setRoutes()
    {
        global $beanList;
        $this->routes = $this->getRoutesData($this->routes_locations);
        foreach (array_keys($beanList) as $module) {
            $module_locations = $this->getModuleLocations($module);
            $this->modules_routes[$module] = $this->getRoutesData($module_locations);
        }
    }

    protected function getModuleLocations($module_name)
    {
        $replace = function ($location) use ($module_name) {
            return str_replace('{module_name}', $module_name, $location);
        };
        return array_map($replace, $this->modules_locations);
    }

    protected function getRoutesData($locations)
    {
        $response = array();
        foreach ($locations as $location) {
            $files = is_dir($location) ? scandir($location) : false;
            if (empty($files)) {
                continue;
            }

            $files = array_diff($files, array('.', '..'));

            foreach ($files as $file) {
                if (strpos($file, ".php") === false) {
                    continue;
                }

                include $location . "/" . $file;
                if (empty($routes)) {
                    continue;
                }
                $response = array_merge($response, $routes);
            }
        }
        return $response;
    }

}
