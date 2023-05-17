<?php

use MintHCM\Api\Controllers\Actions\LoginAction;
use MintHCM\Api\Middlewares\Params\ParamTypes\ArrayType;

$routes = array(
    "get_login" => array(
        "method" => "GET",
        "path" => "/login",
        "class" => LoginAction::class,
        "desc" => "Initial data for first page load",
        "options" => array(
            'auth' => false,
        ),
        "pathParams" => array(),
        "queryParams" => array(),
        "bodyParams" => array(),
    ),
);
