<?php

namespace MintHCM\Api\Controllers;

use Slim\Psr7\Response;
use MintHCM\Lib\Search\Search;
use MintHCM\Utils\LegacyConnector;
use Psr\Http\Message\ServerRequestInterface as Request;

class GlobalSearchController
{

    public function getData(Request $request, Response $response, array $args): Response
    {
        $response = $response->withHeader('Content-type', 'application/json');
        try {
            $search_manager = Search::getManager();
            $search_manager->setQuery(array(
                "search" => 'global',
                "fields" => array("name.*^5", "_all"),
                "items" => 5,
                "query" => $request->getAttribute('query'),
                "sort_order" => "desc",
            ));
            $search_result = $search_manager->search(true);

        } catch (BadRequest400Exception $e) {
            throw new HttpBadRequestException($this->request);
        } catch (InvalidArgumentException $e) {
            throw new HttpInternalServerErrorException($this->request);
        }

        $data = array(
            'query' => $request->getAttribute('query'),
            'next_page_exists' => $search_result->getNextPageExists(),
            'results' => $this->getBeans($search_result->getBeans()),
        );

        $response->getBody()->write(json_encode($data));
        return $response;
    }

    protected function getBeans($beans)
    {
        $response = array();
        foreach ($beans as $bean) {
            $response[] = array(
                "id" => $bean->id,
                "module" => $bean->module_name,
                "name" => $bean->name,
                "meta" => $this->getAdditionalMetaData($bean),
            );
        }
        return $response;
    }
    protected function getAdditionalMetaData($bean)
    {
        $meta_field_name = $GLOBALS['dictionary'][$bean->object_name]['full_text_search_meta_field'] ?? null;
        if (!empty($meta_field_name) && isset($bean->field_defs[$meta_field_name])) {
            $field = $bean->field_defs[$meta_field_name];
        } else {
            $field = $bean->field_defs['date_entered'];
        }
        return [
            "def" => $field,
            "value" => $bean->{$field['name']},
        ];
    }
}
