<?php

use MintHCM\Api\Controllers\AuthController;
use MintHCM\Api\Middlewares\Params\ParamTypes\EmailType;
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
            "login_language" => array(
                "type" => StringType::class,
                "required" => false,
                "desc" => "System language",
                "example" => 'pl_PL',
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
    "forget_password" => array(
        "method" => "POST",
        "path" => "/forget_password",
        "class" => AuthController::class,
        "function" => 'forgetPassword',
        "desc" => "Send mail with link to reset password",
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
            "email" => array(
                "type" => EmailType::class,
                "required" => true,
                "desc" => "Primary email address",
                "example" => 'user@example.com',
            ),
        ),
    ),
    "valid_token" => array(
        "method" => "GET",
        "path" => "/validation_token",
        "class" => AuthController::class,
        "function" => 'validToken',
        "desc" => "Valid forget password token",
        "options" => array(
            'auth' => false,
        ),
        "pathParams" => array(),
        "queryParams" => array(
            "token" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Guid to reset password",
                "example" => '?token=d41448ca-7d9e-1b9f-d826-64621b33ffb1',
            ),
        ),
        "bodyParams" => array(
        ),
    ),
    "reset_forget_password" => array(
        "method" => "POST",
        "path" => "/reset_forget_password",
        "class" => AuthController::class,
        "function" => 'resetForgetPassword',
        "desc" => "Set new password from forget password action",
        "options" => array(
            'auth' => false,
        ),
        "pathParams" => array(),
        "queryParams" => array(),
        "bodyParams" => array(
            "token" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Guid to reset password",
                "example" => '?token=d41448ca-7d9e-1b9f-d826-64621b33ffb1',
            ),
            "username" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Username",
                "example" => 'user',
            ),
            "new_password" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "New password",
                "example" => 'P4$$w0rd',
            ),
        ),
    ),
);
