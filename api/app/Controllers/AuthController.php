<?php

namespace MintHCM\Api\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Psr7\Response;

class AuthController
{

    public function login(Request $request, Response $response, array $args): Response
    {
        $params = $request->getParsedBody();
        chdir('../legacy/');
        require_once 'include/MVC/SugarApplication.php';
        $app = new \SugarApplication();
        $app->startSession();
        require_once 'modules/Users/authentication/SugarAuthenticate/SugarAuthenticateUser.php';
        require_once 'modules/Users/authentication/AuthenticationController.php';
        $sugar_auth = \AuthenticationController::getInstance();
        $loginSuccess = $sugar_auth->login($params['username'], $params['password']);
        chdir('../api/');

        if (!$loginSuccess) {
            throw new HttpUnauthorizedException($request);
        }

        $data = json_encode(['message' => 'Login success']);
        $response->getBody()->write($data);
        return $response;
    }

    public function logout(Request $request, Response $response, array $args): Response
    {
        session_start();
        session_destroy();
        ob_clean();
        sugar_cleanup(true);
        return $response;
    }
}
