<?php

use MintHCM\Api\Controllers\Init\Module;
use MintHCM\Api\Controllers\ModuleController;
use MintHCM\Api\Controllers\Module\ListController;
use MintHCM\Api\Controllers\Module\ListInitController;
use MintHCM\Api\Controllers\Module\ListMassActionsController;
use MintHCM\Api\Middlewares\Params\ParamTypes\IntType;
use MintHCM\Api\Middlewares\Params\ParamTypes\ArrayType;
use MintHCM\Api\Middlewares\Params\ParamTypes\StringType;

$routes = array(
    "detail" => array(
        "method" => "GET",
        "path" => "/Detail/{id}",
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
    "delete" => array(
        "method" => "DELETE",
        "path" => "/{id}",
        "class" => ModuleController::class,
        "function" => 'delete',
        "desc" => "Delete record",
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
        "queryParams" => array(),
        "bodyParams" => array(),
    ),
    "list_data" => array(
        "method" => "POST",
        "path" => "",
        "class" => ListController::class,
        "desc" => "Get list of module beans",
        "options" => array(
            'auth' => true,
        ),
        "bodyParams" => array(
            "offset" => array(
                "type" => IntType::class,
                "required" => true,
                "desc" => "Offset to start searching - in response get info about it, first page default has -1.
                     This number can be greater than items x page becouse user can not access to some rekords",
                "example" => '22',
            ),
            "items" => array(
                "type" => IntType::class,
                "required" => false,
                "desc" => "Items number per page",
                "example" => '25',
            ),
            "sortBy" => array(
                "type" => StringType::class,
                "required" => false,
                "desc" => "Name of field to sort list by them",
                "example" => 'phone_mobile',
            ),
            "sortOrder" => array(
                "type" => StringType::class,
                "required" => false,
                "desc" => "Sort order",
                "example" => 'desc or asc',
            ),
            "filters" => array(
                "type" => ArrayType::class,
                "required" => false,
                "desc" => "Array of filters",
                "example" => '
                    "filters": [
                        {
                            "field": "city",
                            "operator": "equals",
                            "value": "Paris",
                            "not": false/true => default false
                        },
                        {
                            "field": "country",
                            "operator": "match",
                            "value": "USA"
                        },
                    ]
                ',
            ),
        ),
    ),
    "list" => array(
        "method" => "GET",
        "path" => "",
        "class" => ListInitController::class,
        "desc" => "Get init data for list",
        "options" => array(
            'auth' => true,
        ),
    ),
    "init" => array(
        "method" => "GET",
        "path" => "/init",
        "class" => Module::class,
        "desc" => "Get init data for list",
        "options" => array(
            'auth' => true,
        ),
    ),
    "mass_actions" => array(
        "method" => "POST",
        "path" => "/MassActions/{action}",
        "class" => ListMassActionsController::class,
        "desc" => "Mass actions on list of records",
        "options" => array(
            'auth' => true,
        ),
        "pathParams" => array(
            "action" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Action name",
                "example" => 'Delete',
            ),
        ),
        "bodyParams" => array(
            "ids" => array(
                "type" => ArrayType::class,
                "required" => true,
                "desc" => "Array of ids",
                "example" => '["223dee27-b9e7-432a-8da9-c84cc0770035", "223dee27-b9e7-432a-8da9-c84cc0770035"]',
            ),
        ),
    ),
);
