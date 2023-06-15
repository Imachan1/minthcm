<?php

$module_name = 'Project';
$ESListViewDefs['Project'] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'priority' => [
            'default' => true,
        ],
        'status' => [
            'default' => true,
        ],
        'estimated_start_date' => [
            'default' => true,
        ],
        'assigned_user_name' => [
            'default' => true,
        ],
        'estimated_end_date' => [
            'default' => true,
        ],
    ],
    'search' => [
        'name' => [
        ],
        'estimated_start_date' => [
        ],
        'estimated_end_date' => [
        ],
        'status' => [
        ],
        'priority' => [
        ],
    ],
];
