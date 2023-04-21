<?php

namespace MintHCM\Modules\Alerts\api\controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;

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
        require_once "modules/Alerts/controller.php";
        $controller = new \AlertsController();
        $controller->action_get();
        $alerts = $controller->view_object_map['Results'];
        chdir('../api/');

        $response = array();
        if (!is_array($alerts)) {
            return $response;
        }

        foreach ($alerts as $alert) {
            if (empty($alert->id)) {
                continue;
            }
            $response[] = array(
                'id' => $alert->id,
                'name' => $alert->name,
                'description' => $alert->description,
                'is_read' => $alert->is_read,
                'is_closed' => $alert->is_closed,
                'alert_type' => $alert->alert_type,
                'parent_type' => $alert->parent_type,
                'parent_id' => $alert->parent_id,
                'type' => $alert->type,
                'target_module' => $alert->target_module,
            );

        }
        return $response;
    }

}
