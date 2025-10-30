<?php

$viewdefs['Meetings'] = [
    'order' => ['header', 'basicInfo', 'scheduler', 'subpanels'],
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
                    'Duplicate',
                ],
            ],
        ],
        'basicInfo' => [
            'component' => 'MintPanelRecordDetails',
            'data' => [
                'sections' => [
                    'basic' => [
                        'title' => 'LBL_BASIC',
                        'fields' => [
                            ['name', 'status', 'type'],
                            ['date_start', 'date_end'],
                            ['assigned_user_name', 'description']
                        ],
                    ],
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
