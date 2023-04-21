<?php

namespace MintHCM\Api\Controllers\Actions;

use MintHCM\Api\Controllers\Actions\LanguagesAction;
use MintHCM\Api\Controllers\Actions\PreferencesAction;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;

class InitAction
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $response = $response->withHeader('Content-type', 'application/json');

        $response_body = array();
        $response_body['user'] = $this->getCurrentUserData();
        $preferences = $this->getPreferences();
        $response_body['preferences'] = $preferences['preferences'];
        $response_body['global'] = $preferences['global'];
        $response_body['languages'] = $this->getLanguages();
        $response_body['modules'] = $this->getModuleList();
        $response_body['quick_create'] = $this->getQuickCreate();

        $response->getBody()->write(json_encode($response_body));
        return $response;
    }

    private function getCurrentUserData()
    {
        global $current_user;
        if (empty($current_user->id)) {
            return array();
        }

        return array(
            "id" => $current_user->id,
            "is_admin" => "1" === $current_user->is_admin ? true : false,
            "first_name" => $current_user->first_name,
            "last_name" => $current_user->last_name,
            "full_name" => $current_user->full_name,
        );
    }

    private function getPreferences()
    {
        $pref_action = new PreferencesAction();
        return $pref_action->getPreferences();
    }

    private function getLanguages()
    {
        $lang_action = new LanguagesAction();
        return $lang_action->getLanguages();
    }

    private function getModuleList()
    {
        global $current_user, $app_list_strings;
        $modules = query_module_access_list($current_user);
        $modules_icons = include "constants/module_icons.php";
        $action_icons = include "constants/menu_icons.php";
        $response = array();
        if (!is_array($modules)) {
            return $response;
        }

        foreach ($modules as $module) {
            $response[] = array(
                "name" => $app_list_strings['moduleList'][$module],
                "label" => $module,
                "icon" => $modules_icons[$label] ?? $modules_icons['default'],
                "actions" => 'Home' === $module ? $this->getHomeActions() : $this->getModuleMenu($module, $action_icons),
            );
        }
        return $response;
    }

    private function getHomeActions()
    {
        $pref_action = new PreferencesAction();
        $user_pref = $pref_action->getUserAllPreferences();

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

    private function getModuleMenu($module, $icons)
    {
        chdir('../legacy/');
        $sugar_view = new \SugarView();
        $menu = $sugar_view->getMenu($module);
        chdir('../api/');

        $response = array();
        foreach ($menu as $item) {
            $row = array(
                "url" => $item[0],
                "name" => $item[1],
                "action" => $item[2],
                "icon" => $icons[strtolower($item[2])] ?? $icons['default'],
            );
            if (isset($item[3])) {
                $row['module'] = $item[3];
            }
            $response[] = $row;
        }

        return $response;
    }

    private function getQuickCreate()
    {
        $modules = include "constants/quick_create.php";
        $response = array();

        if (!is_array($modules)) {
            return $response;
        }

        foreach ($modules as $module => $name) {
            $response[] = array(
                "module" => $module,
                "name" => $name,
            );
        }
        return $response;
    }
}
