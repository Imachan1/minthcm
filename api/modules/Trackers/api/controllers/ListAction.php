<?php

namespace MintHCM\Modules\Trackers\api\controllers;

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
        global $current_user;

        chdir('../legacy/');
        $tracker = BeanFactory::getBean('Trackers');
        $history = $tracker->get_recently_viewed($current_user->id);
        chdir('../api/');

        return $history;
    }

}
