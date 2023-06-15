<?php

$module_name = 'ResponsibilityActivities';
$ESListViewDefs['ResponsibilityActivities'] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'date_modified' => [
            'default' => true,
        ],
        'date_entered' => [
            'default' => true,
        ],
        'assigned_user_name' => [
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
        'date_modified' => [
        ],
        'date_entered' => [
        ],
        'modified_user_id' => [
        ],
        'created_by' => [
        ],
        'assigned_user_id' => [
        ],
    ],
];
