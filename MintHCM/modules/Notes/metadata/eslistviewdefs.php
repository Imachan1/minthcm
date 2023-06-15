<?php

$module_name = 'Notes';
$ESListViewDefs['Notes'] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'parent_name' => [
            'link' => true,
            'default' => true,
        ],
        'filename' => [
            'default' => true,
        ],
        'created_by_name' => [
            'default' => true,
        ],
        'date_modified' => [
        ],
        'date_entered' => [
            'default' => true,
        ],
    ],
    'search' => [
        'name' => [
        ],
        'parent_name' => [
        ],
        'filename' => [
        ],
        'date_entered' => [
        ],
    ],
];
