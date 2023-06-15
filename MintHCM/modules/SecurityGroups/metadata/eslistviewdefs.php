<?php

$module_name = 'SecurityGroups';
$ESListViewDefs['SecurityGroups'] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'group_type' => [
            'default' => true,
        ],
        'parent_name' => [
            'link' => true,
            'default' => true,
        ],
        'position_leader_name' => [
            'link' => true,
            'default' => true,
        ],
        'assigned_user_name' => [
            'default' => true,
        ],
        'noninheritable' => [
            'default' => true,
        ],
        'current_manager_name' => [
            'link' => true,
            'default' => true,
        ],
    ],
    'search' => [
        'name' => [
        ],
        'group_type' => [
        ],
        'assigned_user_id' => [
        ],
        'position_leader_name' => [
        ],
        'parent_name' => [
        ],
        'date_entered' => [
        ],
        'date_modified' => [
        ],
        'current_manager_name' => [
        ],
    ],
];
