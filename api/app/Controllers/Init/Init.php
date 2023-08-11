<?php

namespace MintHCM\Api\Controllers\Init;

use BeanFactory;
use Slim\Psr7\Response;
use MintHCM\Api\Controllers\Init\Module;
use MintHCM\Api\Controllers\Init\Languages;
use MintHCM\Api\Controllers\Init\Preferences;
use Psr\Http\Message\ServerRequestInterface as Request;

class Init
{
    protected $preferences_controller, $languages_controller, $module_init_controller;

    const VIEW_META = [
        "DetailView",
        "EditView",
        "Subpanels",
        "RecordView",
    ];

    public function __construct()
    {
        $this->preferences_controller = new Preferences();
        $this->languages_controller = new Languages();
        $this->module_init_controller = new Module();
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $response = $response->withHeader('Content-type', 'application/json');

        $response_body = $this->getData();

        $response->getBody()->write(json_encode($response_body));
        return $response;
    }

    public function getData()
    {
        $response_body = array();
        $response_body['languages'] = $this->languages_controller->getLanguages();
        $response_body['user'] = $this->getCurrentUserData();
        $response_body['preferences'] = $this->preferences_controller->getUserPreferences();
        $response_body['global'] = $this->preferences_controller->getGlobalSettings();
        [$modules_menu, $modules_data] = $this->getModules();
        $response_body['menu_modules'] = $modules_menu;
        $response_body['modules'] = $modules_data;
        $response_body['quick_create'] = $this->getQuickCreate();
        $response_body['legacy_views'] = $this->getLegacyViews();
        return $response_body;
    }

    private function getCurrentUserData()
    {
        global $current_user;
        if (empty($current_user->id)) {
            return array();
        }
        $preferences = [];
        $preferences['date_time_preferences'] = $current_user->getUserDateTimePreferences();
        $preferences['first_day_of_week'] = $current_user->getPreference('fdow');
        $preferences['timezone'] = $current_user->getPreference('timezone');
        $preferences['name_format'] = $current_user->getPreference('default_locale_name_format');
        return array(
            "id" => $current_user->id,
            "is_admin" => "1" === $current_user->is_admin ? true : false,
            "first_name" => $current_user->first_name,
            "last_name" => $current_user->last_name,
            "full_name" => $current_user->full_name,
            "email" => $current_user->email1,
            "preferences" => $preferences,
            "show_login_wizard" => empty($current_user->getPreference('ut')),
        );
    }

    private function getModules()
    {
        global $current_user, $app_list_strings;
        chdir('../legacy');
        $modules = query_module_access_list($current_user);
        chdir('../api');
        $modules_data = array();
        if (!is_array($modules)) {
            return $modules_data;
        }
        foreach ($modules as $module) {
            $modules_data[$module] = $this->module_init_controller->getModuleData($module);
        }
        return $this->getMenuForAllModules($modules_data,$modules);
    }

    private function getQuickCreate()
    {
        chdir('../api');
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

    private function getLegacyViews()
    {
        $legacy_views = include "constants/legacy_views.php";
        return $legacy_views;
    }

    private function getMenuForAllModules($modules_data,$modules)
    {
        global $beanList,$current_user;
        foreach($beanList as $key=>$module) {
            if(!array_key_exists($key,$modules_data)){
                if($current_user->isAdmin()){
                    $modules_data[$key] = $this->module_init_controller->getModuleData($key);
                }
            }
        }
        return [array_keys($modules), $modules_data];
    }
}
