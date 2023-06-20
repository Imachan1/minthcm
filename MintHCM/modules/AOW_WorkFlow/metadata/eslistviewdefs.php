<?php

$module_name = 'AOW_WorkFlow';
$ESListViewDefs['AOW_WorkFlow'] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'flow_module' => [
            'default' => true,
        ],
        'status' => [
            'default' => true,
        ],
        'assigned_user_name' => [
            'default' => true,
        ],
        'date_entered' => [
            'default' => true,
        ],
        'date_modified' => [
            'default' => true,
        ],
    ],
    'search' => [
        'name' => [
        ],
        'status' => [
        ],
        'assigned_user_id' => [
        ],
    ],
];
