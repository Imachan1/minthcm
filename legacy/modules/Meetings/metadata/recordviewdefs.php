<?php

$viewdefs['Meetings'] = [
    'order' => ['header', 'overview', 'scheduler', 'subpanels'],
    'panels' => [
        'header' => [
            'component' => 'MintPanelRecordHeader',
            'data' => [
                'fields' => [
                    ['name', 'status'],
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
                    ['name', 'status', 'type'],
                    ['date_start', 'duration', 'date_end'],
                    ['assigned_user_name', 'description']
                ],
            ],
        ],
        'scheduler' => [
            'component' => 'MintPanelScheduler',
        ],
        'subpanels' => [
            'component' => 'MintPanelSubpanels',
        ],
    ],
];
