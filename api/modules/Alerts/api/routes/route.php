<?php

use MintHCM\Api\Middlewares\Params\ParamTypes\ArrayType;
use MintHCM\Api\Middlewares\Params\ParamTypes\BoolType;
use MintHCM\Api\Middlewares\Params\ParamTypes\StringType;
use MintHCM\Modules\Alerts\api\controllers\ListAction;
use MintHCM\Modules\Alerts\api\controllers\MassActionController;
use MintHCM\Modules\Alerts\api\controllers\UpdateAction;

$routes = array(
    "detail" => array(),
    "list_data" => array(),
    "list" => array(
        "method" => "GET",
        "path" => "",
        "class" => ListAction::class,
        "desc" => "Get modules list",
        "options" => array(
            'auth' => true,
        ),
    ),
    "update" => array(
        "method" => "PATCH",
        "path" => "/{id}",
        "class" => UpdateAction::class,
        "desc" => "Update module fields from body",
        "options" => array(
            'auth' => true,
        ),
        "pathParams" => array(
            "id" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Module id",
                "example" => '223dee27-b9e7-432a-8da9-c84cc0770035',
            ),
        ),
        "bodyParams" => array(
            "is_read" => array(
                "type" => BoolType::class,
                "required" => false,
                "desc" => "Set alert as readed",
                "example" => 'true or 1 or "1"',
            ),
            "is_closed" => array(
                "type" => BoolType::class,
                "required" => false,
                "desc" => "Set alert as closed",
                "example" => 'false or 0 or "0"',
            ),
        ),
    ),
    "readAlerts" => [
        "method" => "PATCH",
        "path" => "/update/ReadAlerts",
        "class" => MassActionController::class,
        "desc" => "Mark passed alerts as read",
        "function" => 'readAlerts',
        "options" => [
            'auth' => true,
        ],
        "pathParams" => [],
        "bodyParams" => [ 
            "records" => [
                "type" => ArrayType::class,
                "required" => false,
                "example" => '
                {
                    "records":[
                        "223dee27-b9e7-432a-8da9-c84cc0770035",
                        "723wee27-b9e7-432a-8da9-c831c0770031",
                        "1236ee27-b9e7-432a-8da9-c84c4fsa0033",
                    ]
                }
                ',
            ],
        ],
    ],
    "closeAlerts" => [
        "method" => "PATCH",
        "path" => "/update/CloseAlerts",
        "class" => MassActionController::class,
        "desc" => "Mark passed alerts as closed",
        "function" => 'closeAlerts',
        "options" => [
            'auth' => true,
        ],
        "pathParams" => [],
        "bodyParams" => [ 
            "records" => [
                "type" => ArrayType::class,
                "required" => false,
                "desc" => "Set alert as readed",
                "example" => '
                {
                    "records":[
                        "223dee27-b9e7-432a-8da9-c84cc0770035",
                        "723wee27-b9e7-432a-8da9-c831c0770031",
                        "1236ee27-b9e7-432a-8da9-c84c4fsa0033",
                    ]
                }
                ',
            ],
        ],
    ],
);
