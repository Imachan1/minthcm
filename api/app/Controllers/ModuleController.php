<?php

namespace MintHCM\Api\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Factory\ServerRequestFactory;
use Slim\Psr7\Response;

class ModuleController
{

    public function detail(Request $request, Response $response, array $args): Response
    {
        $data = ['message' => 'detail'];
        $response->getBody()->write(json_encode($data));
        return $response;
    }

    function list(Request $request, Response $response, array $args): Response {
        $data = ['message' => 'list'];
        $response = $GLOBALS["app"]->handle((new ServerRequestFactory())->createServerRequest('GET', 'new_mint/api/Calls/asdasd'));
        $response->getBody()->write(json_encode($data));
        return $response;
    }
}
