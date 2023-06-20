<?php

$module_name = 'AOR_Reports';
$ESListViewDefs['AOR_Reports'] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'report_module' => [
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
    ],
    'search' => [
        'name' => [
        ],
        'assigned_user_id' => [
        ],
    ],
];
