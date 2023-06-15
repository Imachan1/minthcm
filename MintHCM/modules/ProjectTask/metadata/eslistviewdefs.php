<?php

$module_name = 'ProjectTask';
$ESListViewDefs['ProjectTask'] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'project_name' => [
            'link' => true,
            'default' => true,
        ],
        'date_start' => [
            'default' => true,
        ],
        'date_finish' => [
            'default' => true,
        ],
        'assigned_user_name' => [
            'default' => true,
        ],
        'priority' => [
            'default' => true,
        ],
        'percent_complete' => [
            'default' => true,
        ],
    ],
    'search' => [
        'name' => [
        ],
        'project_name' => [
        ],
        'assigned_user_id' => [
        ],
    ],
];
