<?php

use MintHCM\Api\Controllers\Init\Init;
use MintHCM\Api\Controllers\Init\Languages;
use MintHCM\Api\Middlewares\Params\ParamTypes\ArrayType;
use MintHCM\Api\Middlewares\Params\ParamTypes\StringType;

$routes = array(
    "init" => array(
        "method" => "GET",
        "path" => "/init",
        "class" => Init::class,
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
        "class" => Languages::class,
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
            'lang' => array(
                "type" => StringType::class,
                "required" => false,
                "desc" => "Select lang. Return default user or system langugage if selected not exists.",
                "example" => '?lang=en_us',
            ),
        ),
        "bodyParams" => array(),
    ),
);
