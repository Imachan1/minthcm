<?php

namespace MintHCM\Api\Middlewares\Params;

use MintHCM\Api\Middlewares\Middleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Exception\HttpBadRequestException;

class ParamsMiddleware extends Middleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $route = $this->getRoute($request);
        $route_data = $this->getRouteData($request);
        $params = $request->getQueryParams();
        $bodyParams = $request->getParsedBody() ?? [];
        $this->checkPathParams($request, $route_data['pathParams'] ?? []);
        $this->validationParams($request, $params, $route_data['queryParams'] ?? [], 'queryParams');
        $this->validationParams($request, $bodyParams, $route_data['bodyParams'] ?? [], 'bodyParams');

        return $handler->handle($request);
    }

    protected function checkPathParams(Request &$request, array $params_data)
    {
        $route = $this->getRoute($request);
        $path_params = $route->getArguments();
        foreach ($params_data as $name => $data) {
            if (!$data || !is_array($data) || !class_exists($data['type'])) {
                throw new HttpBadRequestException($request);
            }
            $class = new $data['type'];
            $parsed_value = $class($request, $path_params[$name], $data['required'] ?? true);
            $request = $request->withAttribute($name, $parsed_value);
        }
    }

    protected function validationParams(Request &$request, array $params, array $params_data, string $type)
    {
        $globalParams = $this->getGlobalAcceptedParams($type);
        $params = array_diff_key($params, array_fill_keys($globalParams, $globalParams));
        if (empty($params_data) && !empty($params)) {
            throw new HttpBadRequestException($request);
        }

        $empty_params_data = array_fill_keys(array_keys($params_data), null);
        $merged_params = array_merge($empty_params_data, $params);

        foreach ($merged_params as $name => $value) {
            $data = $params_data[$name] ?? false;
            if (!$data || !is_array($data) || !class_exists($data['type'])) {
                throw new HttpBadRequestException($request);
            }
            $class = new $data['type'];
            $parsed_value = $class($request, $value, $data['required'] ?? false);
            $request = $request->withAttribute($name, $parsed_value);
        }

    }

    protected function getGlobalAcceptedParams(string $type): array
    {
        $global_params = include "app/Constansts/global_params.php";

        return $global_params[$type] ?? array();
    }
}
