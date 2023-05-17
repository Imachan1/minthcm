<?php

namespace MintHCM\Api\Controllers\Init;

use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use Slim\Exception\HttpBadRequestException;
use MintHCM\Api\Controllers\Init\Preferences;
use Psr\Http\Message\ServerRequestInterface as Request;

class Module
{
    protected $preferences_controller, $sugar_view, $modules_icons, $action_icons;

    public function __construct()
    {
        $this->preferences_controller = new Preferences();
        $this->sugar_view = new \SugarView();
        $this->modules_icons = include "constants/module_icons.php";
        $this->action_icons = include "constants/menu_icons.php";
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $response = $response->withHeader('Content-type', 'application/json');

        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();
        $module = explode('/', $route->getPattern())[1] ?? null;
        if(!$module) {
            throw new HttpBadRequestException($request);
        }

        $response->getBody()->write(json_encode($this->getData($module)));
        return $response;
    }

    public function getData($module)
    {
        $response = array();
        $response['lang'] = $this->getModuleLang($module);
        $response['data'] = $this->getModuleData($module);
        return $response;
    }

    public function getModuleLang($module)
    {
        global $current_language;
        chdir('../legacy/');
            $response = return_module_language($current_language, $module);
        chdir('../api/');
        return $response;
    }

    public function getModuleData($module)
    {
        return array(
            "name" => $module,
            "icon" => $this->modules_icons[$module] ?? $this->modules_icons['default'],
            "actions" => 'Home' === $module ? $this->getHomeMenu() : $this->getModuleMenu($module),
        );
    }

    private function getHomeMenu()
    {
        $user_pref = $this->preferences_controller->getUserAllPreferences();

        $response = array();
        if (empty($user_pref["Home"]["pages"])) {
            return $response;
        }

        foreach ($user_pref["Home"]["pages"] as $page) {
            $response[] = array(
                "dashboard_label" => $page['pageTitleLabel'] ?? "",
                "dashboard_name" => $page['pageTitle'] ?? "",
            );
        }
        return $response;
    }

    private function getModuleMenu($module)
    {
        chdir('../legacy/');
        $menu = $this->sugar_view->getMenu($module);
        chdir('../api/');

        $response = array();
        foreach ($menu as $item) {
            $row = array(
                "url" => $item[0],
                "name" => $item[1],
                "action" => $item[2],
                "icon" => $this->action_icons[strtolower($item[2])] ?? $this->action_icons['default'],
            );
            if (isset($item[3])) {
                $row['module'] = $item[3];
            }
            $response[] = $row;
        }

        return $response;
    }
}
