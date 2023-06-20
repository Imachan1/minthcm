<?php

$module_name = 'Improvements';
$ESListViewDefs['Improvements'] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'assigned_user_name' => [
            'default' => true,
        ],
        'employee_name' => [
            'default' => true,
        ],
        'date_entered' => [
            'default' => true,
        ],
        'date_modified' => [
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
        'name' => [
        ],
        'assigned_user_id' => [
        ],
        'date_entered' => [
        ],
        'date_modified' => [
        ],
        'created_by' => [
        ],
        'modified_user_id' => [
        ],
        'employee_id' => [
        ],
    ],
];
