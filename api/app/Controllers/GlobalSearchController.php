<?php

namespace MintHCM\Api\Controllers;

use MintHCM\Lib\Search\Search;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;

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
            $search_result = $search_manager->search(false);

        } catch (BadRequest400Exception $e) {
            throw new HttpBadRequestException($this->request);
        } catch (InvalidArgumentException $e) {
            throw new HttpInternalServerErrorException($this->request);
        }

        $data = $search_result->getBeansAsJsonArray();
        $response->getBody()->write(json_encode($data));
        return $response;
    }
}
