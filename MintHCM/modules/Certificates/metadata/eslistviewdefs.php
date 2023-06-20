<?php

$module_name = 'Certificates';
$ESListViewDefs['Certificates'] = [
    'columns' => [
        'name' => [
            'link' => true,
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
        'date_entered' => [
        ],
        'date_modified' => [
        ],
        'assigned_user_id' => [
        ],
    ],
];
