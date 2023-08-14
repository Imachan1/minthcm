<?php

namespace MintHCM\Api\Controllers\Actions;

use MintHCM\Api\Controllers\Init\Languages;
use MintHCM\Api\Controllers\Init\Preferences;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;

class LoginAction
{

    protected $preferences_controller, $languages_controller;

    public function __construct()
    {
        $this->preferences_controller = new Preferences();
        $this->languages_controller = new Languages();
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $response = $response->withHeader('Content-type', 'application/json');

        $response_body = array();
        $response_body['global'] = $this->preferences_controller->getGlobalSettings();
        global $system_config;
        $response_body['global']['ldap_enabled'] = !empty($system_config->settings['system_ldap_enabled']) && $system_config->settings['system_ldap_enabled'] == true;
        $response_body['languages'] = $this->languages_controller->getLanguages();
        
        $response->getBody()->write(json_encode($response_body));
        return $response;
    }
}
