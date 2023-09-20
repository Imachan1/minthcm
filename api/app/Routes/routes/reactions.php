<?php

use MintHCM\Api\Controllers\ReactionsController;
use MintHCM\Api\Middlewares\Params\ParamTypes\StringType;

$routes = array(
    "react" => array(
        "method" => "POST",
        "path" => "/reactions/{parent_type}/{parent_id}",
        "class" => ReactionsController::class,
        "function" => "react",
        "desc" => "add reaction",
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
            "reaction_type" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "reaction type",
                "example" => "like",
            )
        ),
    ),
    "delete_reaction" => array(
        "method" => "DELETE",
        "path" => "/reactions/{parent_type}/{parent_id}",
        "class" => ReactionsController::class,
        "function" => "delete",
        "desc" => "delete reaction",
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
);
