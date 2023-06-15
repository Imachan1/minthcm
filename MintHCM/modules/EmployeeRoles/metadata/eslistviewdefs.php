<?php

$module_name = 'EmployeeRoles';
$ESListViewDefs['EmployeeRoles'] = [
    'columns' => [
        'name' => [
            'link' => true,
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
