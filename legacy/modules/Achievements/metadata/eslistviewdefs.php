<?php

$module_name = 'Achievements';
$ESListViewDefs['Achievements'] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'icon' => [
            'default' => true,
        ],
        'category' => [
            'default' => true,
        ],
        'award_type' => [
            'default' => true,
        ],
        'visibility' => [
            'default' => true,
        ],
        'active' => [
            'default' => true,
        ],
        'assigned_user_name' => [
            'default' => true,
        ],
        'date_modified' => [
            'default' => false,
        ],
        'date_entered' => [
            'default' => false,
        ],
        'created_by_name' => [
            'default' => false,
        ],
        'modified_by_name' => [
            'link' => true,
            'default' => false,
        ],
    ],
    'search' => [
        'name' => [],
        'category' => [],
        'award_type' => [],
        'visibility' => [],
        'active' => [],
        'assigned_user_name' => [],
        'date_entered' => [],
        'date_modified' => [],
    ],
];
