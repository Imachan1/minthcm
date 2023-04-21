<?php

use MintHCM\Api\Controllers\Actions\InitAction;
use MintHCM\Api\Controllers\Actions\LanguagesAction;
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
    "init" => array(
        "method" => "GET",
        "path" => "/init",
        "class" => InitAction::class,
        "desc" => "Initial data for first page load",
        "options" => array(
            'auth' => true,
        ),
        "pathParams" => array(),
        "queryParams" => array(),
        "bodyParams" => array(),
    ),
    'languages' => array(
        "method" => "GET",
        "path" => "/languages",
        "class" => LanguagesAction::class,
        "desc" => "Get app_strings, app_list_strings and optional modules lang",
        "options" => array(
            'auth' => false,
            'optional_auth' => true,
        ),
        "pathParams" => array(),
        "queryParams" => array(
            'modules' => array(
                "type" => ArrayType::class,
                "required" => false,
                "desc" => "Get lang from this modules",
                "example" => '?module=Calls,Users or ?module[]=Calls&module[]=Users',
            ),
        ),
        "bodyParams" => array(),
    ),
);
