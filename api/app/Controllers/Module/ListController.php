<?php

namespace MintHCM\Api\Controllers\Module;

use Link2;
use M2MRelationship;
use Slim\Psr7\Response;
use MintHCM\Lib\Search\Search;
use Slim\Routing\RouteContext;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Elasticsearch\Common\Exceptions\BadRequest400Exception;
use Elasticsearch\Common\Exceptions\InvalidArgumentException;

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
            'total' => $this->search_result['total'],
            'next_page_exists' => $this->search_result['next_page_exists'],
            'offset' => $this->search_result['next_offset'],
            'results' => $this->getBeans(),
        );
    }

    private function getBeans()
    {
        $beans = array();
        foreach ($this->search_result['beans'] as $bean) {
            $columns = $bean->column_fields;
            $row = [];
            foreach ($columns as $column) {
                if($bean->$column instanceof Link2) {
                    $id_key = strtolower($bean->$column->getSide()) . "_key";
                    $id_name = $bean->$column->relationship->def[$id_key];
                    $id = $bean->{$id_name};
                    if(!empty($id) && $id_name !== 'id') {
                        $row[$column] = "/" . $bean->$column->getRelatedModuleName() . "/DetailView/" . $id;
                        continue;
                    }
                }
                $row[$column] = $bean->$column;
            }
            $row['acl_access'] = $bean->acl_access;
            $beans[] = $row;
        }
        return $beans;
    }

    private function setParams(Request $request)
    {
        global $mint_config;

        $size = $request->getAttribute('items') ?? ($mint_config['search']['default_page_size'] ?? 25);
        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();
        
        $params = array();
        $params['items'] = $size;
        $params['offset'] = $request->getAttribute('offset') ?? -1;
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
            $search_manager->execute($this->params);
            $this->search_result = $search_manager->getResultBeans();

        } catch (BadRequest400Exception $e) {
            throw new HttpBadRequestException($this->request);
        } catch (InvalidArgumentException $e) {
            throw new HttpInternalServerErrorException($this->request);
        }
    }

}
