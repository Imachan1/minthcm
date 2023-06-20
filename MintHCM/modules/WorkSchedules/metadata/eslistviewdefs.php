<?php

$module_name = 'WorkSchedules';
$ESListViewDefs['WorkSchedules'] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'type' => [
            'default' => true,
        ],
        'status' => [
            'default' => true,
        ],
        'supervisor_acceptance' => [
            'default' => true,
        ],
        'assigned_user_name' => [
            'default' => true,
        ],
        'date_start' => [
        ],
        'date_end' => [
        ],
        'spent_time' => [
        ],
        'spent_time_settlement' => [
        ],
        'delegation_duration' => [
        ],
        'workplace_name' => [
            'link' => true,
            'default' => true,
        ],
    ],
    'search' => [
        'assigned_user_id' => [
        ],
        'type' => [
        ],
        'status' => [
        ],
        'supervisor_acceptance' => [
        ],
        'date_start' => [
        ],
        'date_end' => [
        ],
        'delegation_duration' => [
        ],
    ],
];
