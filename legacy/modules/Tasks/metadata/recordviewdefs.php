<?php

$viewdefs['Tasks'] = [
    'order' => ['header', 'overview', 'subpanels'],
    'panels' => [
        'header' => [
            'component' => 'MintPanelRecordHeader',
            'data' => [
                'fields' => [
                    ['name'],
                    ['status'],
                ],
                'actions' => [
                    'Audit',
                    'Delete',
                ],
            ],
        ],
        'overview' => [
            'component' => 'MintPanelRecordDetails',
            'data' => [
                'fields' => [
                    ['name', 'status', 'priority'],
                    [['name' => 'date_start', 'readonly' => true], ['name' => 'date_due', 'readonly' => true], 'assigned_user_name'],
                    [['name' => 'date_entered', 'readonly' => true], ['name' => 'date_modified', 'readonly' => true], 'created_by_name'],
                    ['', '', 'modified_by_name'],
                    ['parent_name', 'description', ''],
                ],
            ],
        ],
        'subpanels' => [
            'component' => 'MintPanelSubpanels',
        ],
    ],
];
