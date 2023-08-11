<?php

namespace MintHCM\Modules\Alerts\api\controllers;

use MintHCM\Modules\Alerts\api\helpers\DataHelper;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;
use Slim\Exception\HttpBadRequestException;

class UpdateAction
{

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $response = $response->withHeader('Content-type', 'application/json');
        if(!$this->saveBean($request)) {
            throw new HttpBadRequestException($request);
        }
        $response->getBody()->write(json_encode(DataHelper::getNewAlerts()));
        return $response;
    }

    public function saveBean(Request $request): bool
    {
        $id = $request->getAttribute('id');
        $is_read = $request->getAttribute('is_read') ?? null;
        $is_closed = $request->getAttribute('is_closed') ?? null;

        chdir('../legacy/');
        $alert = \BeanFactory::getBean('Alerts', $id);
        if (empty($alert->id) || !DataHelper::isAssignedUserCurrentUser($alert)) {
            return false;
        }

        $alert->is_read = null === $is_read ? $alert->is_read : $is_read;
        $alert->is_closed = null === $is_closed ? $alert->is_closed : $is_closed;
        $response = $alert->save(false);
        chdir('../api/');

        return $response ? true : false;
    }

}
