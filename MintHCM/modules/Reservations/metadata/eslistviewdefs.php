<?php

$module_name = 'Reservations';
$ESListViewDefs['Reservations'] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'assigned_user_name' => [
            'default' => true,
        ],
        'starting_date' => [
            'default' => true,
        ],
        'ending_date' => [
            'default' => true,
        ],
        'resource_name' => [
            'link' => true,
            'default' => true,
        ],
        'parent_name' => [
            'link' => true,
            'default' => true,
        ],
        'delegation_name' => [
            'link' => true,
            'default' => true,
        ],
        'employee_name' => [
            'default' => true,
        ],
    ],
    'search' => [
        'name' => [
        ],
        'assigned_user_id' => [
        ],
        'date_entered' => [
        ],
        'date_modified' => [
        ],
        'employee_id' => [
        ],
        'parent_name' => [
        ],
        'resource_name' => [
        ],
        'delegation_name' => [
        ],
    ],
];
