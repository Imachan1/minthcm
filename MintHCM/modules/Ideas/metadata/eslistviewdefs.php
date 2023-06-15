<?php

$module_name = 'Ideas';
$ESListViewDefs['Ideas'] = [
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
        'user_name' => [
            'link' => true,
            'default' => true,
        ],
        'date_modified' => [
            'default' => true,
        ],
        'date_entered' => [
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
        'status' => [
        ],
        'assigned_user_id' => [
        ],
        'user_id' => [
        ],
        'date_entered' => [
        ],
        'date_modified' => [
        ],
    ],
];
