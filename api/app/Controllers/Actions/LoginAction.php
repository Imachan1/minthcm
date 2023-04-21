<?php

namespace MintHCM\Api\Controllers\Actions;

use MintHCM\Api\Controllers\Actions\LanguagesAction;
use MintHCM\Api\Controllers\Actions\PreferencesAction;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;

class LoginAction
{

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $response = $response->withHeader('Content-type', 'application/json');

        $response_body = array();
        $preferences = $this->getPreferences();
        $response_body['global'] = $preferences['global'];
        $response_body['languages'] = $this->getLanguages();

        $response->getBody()->write(json_encode($response_body));
        return $response;
    }

    private function getPreferences()
    {
        $pref_action = new PreferencesAction();
        return $pref_action->getPreferences();
    }

    private function getLanguages()
    {
        $lang_action = new LanguagesAction();
        return $lang_action->getLanguages(['Users']);
    }
}
