<?php

use MintHCM\Api\Controllers\AuthController;
use MintHCM\Api\Middlewares\Params\ParamTypes\StringType;

$routes = array(
    "login" => array(
        "method" => "POST",
        "path" => "/login",
        "class" => AuthController::class,
        "function" => 'login',
        "desc" => "Auth user in MintHCM",
        "options" => array(
            'auth' => false,
        ),
        "pathParams" => array(),
        "queryParams" => array(),
        "bodyParams" => array(
            "username" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Username",
                "example" => 'user',
            ),
            "password" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "User password",
                "example" => 'p4$$w0rd',
            ),
        ),
    ),
    "logout" => array(
        "method" => array("POST", 'get'),
        "path" => "/logout",
        "class" => AuthController::class,
        "function" => 'logout',
        "desc" => "Logout user from MintHCM",
        "options" => array(
            'auth' => false,
        ),
        "pathParams" => array(),
        "queryParams" => array(),
        "bodyParams" => array(),
    ),
);
