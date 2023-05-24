<?php

namespace MintHCM\Api\Controllers\Module;

use Elasticsearch\Common\Exceptions\BadRequest400Exception;
use Elasticsearch\Common\Exceptions\InvalidArgumentException;
use MintHCM\Lib\Search\Search;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;

class ListController
{
    private $request, $params, $search_result, $list_response;

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $this->request = $request;
        $response = $response->withHeader('Content-type', 'application/json');

        $this->setParams($this->request);
        $this->runElasticSearch();
        $data = $this->getData();

        $response->getBody()->write(json_encode($data));
        return $response;
    }

    private function getData()
    {
        return array(
            'total' => $this->search_result->getTotal(),
            'next_page_exists' => $this->search_result->getNextPageExists(),
            'offset' => $this->search_result->getNextOffset(),
            'results' => $this->search_result->getBeansAsJsonArray(),
        );
    }

    private function setParams(Request $request)
    {
        global $mint_config;

        $size = $request->getAttribute('items') ?? ($mint_config['search']['default_page_size'] ?? 25);
        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();

        $params = array();
        $params['search'] = 'list';
        $params['items'] = $size;
        $params['from'] = $request->getAttribute('offset') ?? -1;
        $params['sort_by'] = $request->getAttribute('sortBy') ?? null;
        $params['sort_order'] = $request->getAttribute('sortOrder') ?? 'asc';
        $params['filters'] = $request->getAttribute('filters') ?? array();
        $params['type'] = str_replace('/', '', $route->getPattern());
        $this->params = $params;
    }

    private function runElasticSearch()
    {
        try {
            $search_manager = Search::getManager();
            $search_manager->setQuery($this->params);
            $this->search_result = $search_manager->search(true);

        } catch (BadRequest400Exception $e) {
            throw new HttpBadRequestException($this->request);
        } catch (InvalidArgumentException $e) {
            throw new HttpInternalServerErrorException($this->request);
        }
    }

}
