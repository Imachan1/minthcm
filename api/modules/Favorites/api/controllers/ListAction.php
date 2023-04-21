<?php

namespace MintHCM\Modules\Favorites\api\controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;
use \BeanFactory;

class ListAction
{

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $response = $response->withHeader('Content-type', 'application/json');
        $response->getBody()->write(json_encode($this->getListData()));
        return $response;
    }

    public function getListData()
    {
        chdir('../legacy/');
        $favorites = BeanFactory::getBean('Favorites');
        $favorite_records = $favorites->getCurrentUserSidebarFavorites();
        chdir('../api/');

        return $favorite_records;
    }

}
