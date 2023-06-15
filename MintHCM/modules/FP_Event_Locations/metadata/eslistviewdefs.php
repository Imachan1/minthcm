<?php

$module_name = 'FP_Event_Locations';
$ESListViewDefs['FP_Event_Locations'] = [
    'columns' => [
        'date_entered' => [
            'default' => true,
        ],
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'capacity' => [
            'default' => true,
        ],
        'assigned_user_name' => [
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
