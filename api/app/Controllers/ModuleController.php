<?php

namespace MintHCM\Api\Controllers;

use BeanFactory;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;

class ModuleController
{

    public function detail(Request $request, Response $response, array $args): Response
    {
        $response = $response->withHeader('Content-type', 'application/json');
        $data = ['message' => 'detail'];
        $response->getBody()->write(json_encode($data));
        return $response;
    }

    function list(Request $request, Response $response, array $args): Response{
        $response = $response->withHeader('Content-type', 'application/json');
        $data = ['message' => 'list'];
        $response->getBody()->write(json_encode($data));
        return $response;
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();
        $module = explode('/', $route->getPattern())[1] ?? null;
        $id = $request->getAttribute('id');
        chdir('../legacy/');
        $f = BeanFactory::getBean($module, $id);
        if (empty($f->id)  ) {
            $response = $response->withStatus(404);
            return $response;
        } else {
            if(!$f->ACLAccess('delete')){
                $response = $response->withStatus(403);
                return $response;
            }
        }
        $f->mark_deleted($id);
        chdir('../api/');
        $response = $response->withStatus(200);
        return $response;
    }
}
