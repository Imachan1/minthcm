<?php

namespace MintHCM\Api\Controllers\Actions;

use Doctrine\ORM\EntityManagerInterface;
use MintHCM\Api\Controllers\Init\Languages;
use MintHCM\Api\Controllers\Init\Preferences;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;

class LoginAction
{

    protected $preferences_controller, $languages_controller;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->preferences_controller = new Preferences($entityManager);
        $this->languages_controller = new Languages();
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $response = $response->withHeader('Content-type', 'application/json');

        $response_body = array();
        $response_body['global'] = $this->preferences_controller->getGlobalSettings();
        $response_body['languages'] = $this->languages_controller->getLanguages();

        $response->getBody()->write(json_encode($response_body));
        return $response;
    }
}
