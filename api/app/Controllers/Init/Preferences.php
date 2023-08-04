<?php

namespace MintHCM\Api\Controllers\Init;

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;

class Preferences
{
    protected $user_preferences;

    public function __construct()
    {
        $this->setUserPreferences();
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $response = $response->withHeader('Content-type', 'application/json');
        $response->getBody()->write(json_encode($this->getPreferences()));
        return $response;
    }

    public function getPreferences()
    {
        $response['preferences'] = $this->getUserPreferences();
        $response['global'] = $this->getGlobalSettings();
        return $response;
    }

    public function getGlobalSettings()
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
            'password_rules' => [
                'minpwdlength' => $sugar_config['passwordsetting']['minpwdlength'] ?? null,
                'oneupper' => $sugar_config['passwordsetting']['oneupper'] ?? false,
                'onelower' => $sugar_config['passwordsetting']['onelower'] ?? false,
                'onenumber' => $sugar_config['passwordsetting']['onenumber'] ?? false,
                'onespecial' => $sugar_config['passwordsetting']['onespecial'] ?? false,
            ],
        );
    }

    public function getUserPreferences()
    {
        return array(
            'date_format' => $this->user_preferences['global']['datef'] ?? '',
            'time_format' => $this->user_preferences['global']['timef'] ?? '',
            'name_format' => $this->user_preferences["global"]["default_locale_name_format"] ?? '',
        );
    }

    public function getUserAllPreferences()
    {
        return $this->user_preferences;
    }

    private function setUserPreferences()
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
        $this->user_preferences = $preferences;
    }
}
