<?php

use MintHCM\Api\Controllers\Actions\LoginAction;
use MintHCM\Api\Controllers\GlobalSearchController;
use MintHCM\Api\Middlewares\Params\ParamTypes\StringType;

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
    "global_search" => array(
        "method" => "GET",
        "path" => "/global_search",
        "class" => GlobalSearchController::class,
        "function" => "getData",
        "desc" => "Global search",
        "options" => array(
            'auth' => true,
        ),
        "pathParams" => array(),
        "queryParams" => array(
            'query' => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Query for search",
                "example" => 'Search Admin* OR Kowalski => ?query=Admin%2A%20OR%20Kowalski',
            ),
        ),
        "bodyParams" => array(),
    ),
);
