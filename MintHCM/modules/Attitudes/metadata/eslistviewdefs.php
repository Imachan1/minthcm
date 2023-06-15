<?php

$module_name = 'Attitudes';
$ESListViewDefs['Attitudes'] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'employee_name' => [
            'default' => true,
        ],
        'assigned_user_name' => [
            'default' => true,
        ],
        'description' => [
        ],
        'date_modified' => [
            'default' => true,
        ],
        'date_entered' => [
            'default' => true,
        ],
    ],
    'search' => [
        'name' => [
        ],
        'assigned_user_id' => [
        ],
        'employee_id' => [
        ],
    ],
];
