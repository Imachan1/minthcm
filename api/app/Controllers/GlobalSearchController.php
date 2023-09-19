<?php

namespace MintHCM\Api\Controllers;

use Doctrine\ORM\EntityManagerInterface;
use Slim\Psr7\Response;
use MintHCM\Lib\Search\Search;
use MintHCM\Utils\LegacyConnector;
use Psr\Http\Message\ServerRequestInterface as Request;

use SuiteCRM\Search\SearchQuery;
use SuiteCRM\Search\SearchWrapper;

class GlobalSearchController
{
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        // Workaround for api/lib/Search/Base/SearchResult.php
        // Passing EntityManager by constructor could make a mess with class structure
        // It should be replaced with a normal solution
        global $entityManager;
        $entityManager = $this->entityManager;
    }

    public function getData(Request $request, Response $response, array $args): Response
    {
        $response = $response->withHeader('Content-type', 'application/json');
        try {
            global $current_user;
            $query = $request->getAttribute('query');

            $search_manager = Search::getManager();
            ;
            $search_manager->setElasticACL(!is_admin($current_user));

            $search_manager->setQuery(array(
                "search" => 'global',
                "fields" => array("name.*^5", "*"),
                "items" => 5,
                "query" => $request->getAttribute('query'),
                "sort_order" => "desc",
            ));
            $search_result = $search_manager->search(false);


        } catch (BadRequest400Exception $e) {
            throw new HttpBadRequestException($this->request);
        } catch (InvalidArgumentException $e) {
            throw new HttpInternalServerErrorException($this->request);
        }
        
        $data = array(
            'query' => $query,
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
