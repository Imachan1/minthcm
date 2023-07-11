<?php

$module_name = 'EmployeeCertificates';
$ESListViewDefs['EmployeeCertificates'] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'status' => [
            'default' => true,
        ],
        'start_date' => [
        ],
        'end_date' => [
        ],
        'employee_name' => [
            'default' => true,
        ],
        'candidate_name' => [
            'link' => true,
            'default' => true,
        ],
        'certificate_name' => [
            'link' => true,
            'default' => true,
        ],
        'date_entered' => [
        ],
        'date_modified' => [
        ],
        'assigned_user_name' => [
        ],
        'created_by_name' => [
            'link' => true,
        ],
        'modified_by_name' => [
            'link' => true,
        ],
    ],
    'search' => [
        'name' => [],
        'status' => [],
        'start_date' => [],
        'end_date' => [],
        'candidate_name' => [],
        'employee_name' => [],
        'assigned_user_name' => [],
        'date_entered' => [],
        'date_modified' => [],
        'created_by_name' => [],
        'modified_by_name' => [],
    ],
];
