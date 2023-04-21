<?php

namespace MintHCM\Api\Controllers\Actions;

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;

class PreferencesAction
{

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $response = $response->withHeader('Content-type', 'application/json');
        $response->getBody()->write(json_encode($this->getPreferences()));
        return $response;
    }

    public function getPreferences()
    {
        $current_user_preferences = $this->getUserAllPreferences();
        $response = array();
        $response['preferences'] = $this->getUserPreferences($current_user_preferences);
        $response['global'] = $this->getGlobalSettings();
        return $response;
    }

    public function getUserAllPreferences()
    {
        global $current_user, $sugar_config;
        if (empty($current_user->id)) {
            return array();
        }

        $db = \DBManagerFactory::getInstance();
        $result = $db->query("SELECT contents, category FROM user_preferences WHERE assigned_user_id='$current_user->id' AND deleted = 0", false, 'Failed to load user preferences');
        $preferences = [];
        while ($row = $db->fetchByAssoc($result)) {
            $category = $row['category'];
            $preferences[$category] = unserialize(base64_decode($row['contents']));
        }
        return $preferences;
    }

    private function getUserPreferences(array $current_user_preferences)
    {
        return array(
            'date_format' => $current_user_preferences['global']['datef'] ?? '',
            'time_format' => $current_user_preferences['global']['timef'] ?? '',
            'name_format' => $current_user_preferences["global"]["default_locale_name_format"],
        );
    }

    private function getGlobalSettings()
    {
        global $sugar_config;
        return array(
            'calendar' => $sugar_config['calendar'],
            'currency' => $sugar_config['currency'],
            'date_format' => $sugar_config['datef'],
            'time_format' => $sugar_config['timef'],
            'default_date_format' => $sugar_config["default_date_format"],
            'default_time_format' => $sugar_config["default_time_format"],
            'default_language' => $sugar_config["default_language"],
            'languages' => $sugar_config["languages"],
            'date_formats' => $sugar_config["date_formats"],
            'time_formats' => $sugar_config["time_formats"],
            'name_format' => $sugar_config["default_locale_name_format"],
            'name_formats' => $sugar_config["name_formats"],

        );
    }
}
