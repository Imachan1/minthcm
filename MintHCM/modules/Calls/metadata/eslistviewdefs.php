<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$module_name = 'Calls';
$ESListViewDefs[$module_name] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'direction' => [
            'default' => true,
        ],
        'parent_name' => [
            'link' => true,
            'default' => true,
        ],
        'date_start' => [
            'default' => true,
        ],
        'assigned_user_name' => [
            'link' => true,
            'default' => true,
        ],
        'status' => [],
        'date_entered' => [
            'default' => true,
        ],
        'duration_minutes' => [
            'default' => true,
        ],
    ],
    'search' => [
        'date_entered' => [],
        'date_start' => [],
        'date_end' => [],
        'direction' => [],
        'status' => [],
        'duration_minutes' => [],
    ],
];
