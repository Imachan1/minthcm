<?php

namespace MintHCM\Api\Controllers\Actions;

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;

class LanguagesAction
{

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $response = $response->withHeader('Content-type', 'application/json');

        $modules = $request->getAttribute('modules') ?? array();
        $response_body = $this->getLanguages($modules);

        $response->getBody()->write(json_encode($response_body));
        return $response;
    }

    public function getLanguages(array $modules = []): array
    {
        global $sugar_config, $app_list_strings, $app_strings, $current_language;
        $current_language = $_SESSION["authenticated_user_language"] ?? $sugar_config['default_language'];

        $response = array();
        chdir('../legacy/');
        $app_list_strings = return_app_list_strings_language($current_language);
        $app_strings = return_application_language($current_language);
        $response['app_list_strings'] = $app_list_strings;
        $response['app_strings'] = $app_strings;
        foreach ($modules as $module) {
            $response[$module] = return_module_language($current_language, $module);
        }
        chdir('../api/');
        return $response;
    }
}
