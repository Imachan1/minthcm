<?php

use MintHCM\Api\Controllers\ModuleController;
use MintHCM\Api\Middlewares\Params\ParamTypes\StringType;

$routes = array(
    "detail" => array(
        "method" => "GET",
        "path" => "/{id}",
        "class" => ModuleController::class,
        "function" => 'detail',
        "desc" => "Get module detail",
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
    ),
    "list" => array(
        "method" => "GET",
        "path" => "",
        "class" => ModuleController::class,
        "function" => 'list',
        "desc" => "Get module detail",
        "options" => array(
            'auth' => true,
        ),
    ),
);
