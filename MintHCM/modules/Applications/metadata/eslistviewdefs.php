<?php

$module_name = 'Applications';
$ESListViewDefs['Applications'] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'status' => [
            'default' => true,
        ],
        'type' => [
            'default' => true,
        ],
        'assigned_user_name' => [
            'default' => true,
        ],
        'employee_name' => [
            'default' => true,
        ],
        'date_modified' => [
            'default' => true,
        ],
        'date_entered' => [
            'default' => true,
        ],
        'created_by_name' => [
            'link' => true,
        ],
        'modified_by_name' => [
            'link' => true,
        ],
    ],
    'search' => [
        'search_name' => [
        ],
        'date_modified' => [
        ],
        'date_entered' => [
        ],
        'modified_user_id' => [
        ],
        'created_by' => [
        ],
        'status' => [
        ],
        'type' => [
        ],
        'employee_name' => [
        ],
    ],
];
