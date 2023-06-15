<?php

$module_name = 'KReports';
$ESListViewDefs['KReports'] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'report_module' => [
            'default' => true,
        ],
        'listtype' => [
            'default' => true,
        ],
        'chart_layout' => [
            'default' => true,
        ],
        'description' => [
            'default' => true,
        ],
        'date_entered' => [
            'default' => true,
        ],
        'date_modified' => [
            'default' => true,
        ],
        'assigned_user_name' => [
            'default' => true,
        ],
    ],
    'search' => [
        'name' => [
        ],
        'current_user_only' => [
        ],
        'assigned_user_id' => [
        ],
        'report_module' => [
        ],
    ],
];
