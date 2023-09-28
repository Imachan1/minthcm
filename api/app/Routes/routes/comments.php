<?php

use MintHCM\Api\Controllers\CommentsController;
use MintHCM\Api\Middlewares\Params\ParamTypes\StringType;
use MintHCM\Api\Middlewares\Params\ParamTypes\ArrayType;

$routes = array(
    "get_initial_data" => array(
        "method" => "GET",
        "path" => "/comments/{parent_type}/{parent_id}/init",
        "class" => CommentsController::class,
        "function" => "getInitialData",
        "desc" => "get initial data for comments",
        "options" => array(
            "auth" => true,
        ),
        "pathParams" => array(
            "parent_type" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Parent module",
                "example" => "Tasks",
            ),
            "parent_id" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Parent ID",
                "example" => "223dee27-b9e7-432a-8da9-c84cc0770035",
            ),
        ),
        "queryParams" => array(),
        "bodyParams" => array(),
    ),
    "get" => array(
        "method" => "GET",
        "path" => "/comments/{parent_type}/{parent_id}",
        "class" => CommentsController::class,
        "function" => "get",
        "desc" => "get comments",
        "options" => array(
            "auth" => true,
        ),
        "pathParams" => array(
            "parent_type" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Parent module",
                "example" => "Tasks",
            ),
            "parent_id" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Parent ID",
                "example" => "223dee27-b9e7-432a-8da9-c84cc0770035",
            ),
        ),
        "queryParams" => array(),
        "bodyParams" => array(),
    ),
    "create" => array(
        "method" => "POST",
        "path" => "/comments/{parent_type}/{parent_id}",
        "class" => CommentsController::class,
        "function" => "create",
        "desc" => "create comment",
        "options" => array(
            "auth" => true,
        ),
        "pathParams" => array(
            "parent_type" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Parent module",
                "example" => "Tasks",
            ),
            "parent_id" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Parent ID",
                "example" => "223dee27-b9e7-432a-8da9-c84cc0770035",
            ),
        ),
        "queryParams" => array(),
        "bodyParams" => array(
            "description" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "comment body",
                "example" => "Hello <strong>World!</strong>",
            ),
            "reply_to_id" => array(
                "type" => StringType::class,
                "required" => false,
                "desc" => "reply to comment id",
                "example" => "223dee27-b9e7-432a-8da9-c84cc0770035",
            ),
        ),
    ),
    "update" => array(
        "method" => "PATCH",
        "path" => "/comments/{parent_type}/{parent_id}/{id}",
        "class" => CommentsController::class,
        "function" => "update",
        "desc" => "update comment",
        "options" => array(
            "auth" => true,
        ),
        "pathParams" => array(
            "parent_type" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Parent module",
                "example" => "Tasks",
            ),
            "parent_id" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Parent ID",
                "example" => "223dee27-b9e7-432a-8da9-c84cc0770035",
            ),
            "id" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Comment ID",
                "example" => "223dee27-b9e7-432a-8da9-c84cc0770035",
            ),
        ),
        "queryParams" => array(),
        "bodyParams" => array(
            "attributes" => array(
                "type" => ArrayType::class,
                "required" => true,
                "desc" => "comment fields",
                "example" => '
                    "attributes": {
                        "pinned": false
                    },
                ',
            ),
        ),
    ),
);
