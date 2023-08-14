<?php

namespace MintHCM\Api\Controllers;

use Doctrine\ORM\EntityManagerInterface;
use Slim\Psr7\Response;
use MintHCM\Lib\Search\Search;
use MintHCM\Utils\LegacyConnector;
use Psr\Http\Message\ServerRequestInterface as Request;

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
        global $app_strings;
        $timedate = new LegacyConnector("TimeDate");
        $response = array();

        foreach ($beans as $bean) {
            $response[] = array(
                "id" => $bean->id,
                "module" => $bean->module_name,
                "name" => $bean->name,
                "meta" => array(
                    "label" => $app_strings['LBL_DATE_ENTERED'],
                    "value" => $timedate->asUser($timedate->fromString($bean->date_entered)),
                ),
            );
        }
        return $response;
    }
}
