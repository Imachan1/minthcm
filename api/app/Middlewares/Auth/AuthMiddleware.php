<?php

namespace MintHCM\Api\Middlewares\Auth;

use MintHCM\Api\Middlewares\Middleware;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Psr7\Response;

class AuthMiddleware extends Middleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        [$runLogic, $optionalAuth] = $this->getAuthOptions($request);
        if ($runLogic || $optionalAuth) {
            session_start();
            chdir('../legacy/');
            require_once 'modules/Users/authentication/AuthenticationController.php';
            $sugar_auth = \AuthenticationController::getInstance();
            $authenticated = $sugar_auth->sessionAuthenticate();
            chdir('../api/');
            if ($authenticated || $optionalAuth) {
                return $handler->handle($request);
            }

            throw new HttpUnauthorizedException($request);
        }

        return $handler->handle($request);
    }

    protected function getAuthOptions(Request $request): array
    {
        $route_data = $this->getRouteData($request);
        return [$route_data['options']['auth'] ?? true, $route_data['options']['optional_auth'] ?? false];
    }
}
