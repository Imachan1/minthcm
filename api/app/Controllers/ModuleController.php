<?php

namespace MintHCM\Api\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;

class ModuleController
{

    public function detail(Request $request, Response $response, array $args): Response
    {
        $response = $response->withHeader('Content-type', 'application/json');
        $data = ['message' => 'detail'];
        $response->getBody()->write(json_encode($data));
        return $response;
    }

    function list(Request $request, Response $response, array $args): Response {
        $response = $response->withHeader('Content-type', 'application/json');
        $data = ['message' => 'list'];
        $response->getBody()->write(json_encode($data));
        return $response;
    }
}
