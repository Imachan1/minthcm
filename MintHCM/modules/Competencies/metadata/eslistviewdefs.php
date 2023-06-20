<?php

$module_name = 'Competencies';
$ESListViewDefs['Competencies'] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'competencies_type' => [
            'default' => true,
        ],
        'date_modified' => [
            'default' => true,
        ],
        'assigned_user_name' => [
            'default' => true,
        ],
        'employee_name' => [
            'default' => true,
        ],
        'date_entered' => [
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
        'competencies_type' => [
        ],
        'date_entered' => [
        ],
        'date_modified' => [
        ],
        'assigned_user_id' => [
        ],
        'employee_id' => [
        ],
    ],
];
