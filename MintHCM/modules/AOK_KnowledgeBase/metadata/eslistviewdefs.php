<?php

$module_name = 'AOK_KnowledgeBase';
$ESListViewDefs['AOK_KnowledgeBase'] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'status' => [
            'default' => true,
        ],
        'author' => [
            'link' => true,
            'default' => true,
        ],
        'approver' => [
            'link' => true,
            'default' => true,
        ],
        'revision' => [
            'default' => true,
        ],
        'date_entered' => [
            'default' => true,
        ],
        'date_modified' => [
            'default' => true,
        ],
        'assigned_user_name' => [
        ],
    ],
    'search' => [
        'name' => [
        ],
        'assigned_user_id' => [
        ],
    ],
];
