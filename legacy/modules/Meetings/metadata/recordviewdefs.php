<?php

$viewdefs['Meetings'] = [
    'order' => ['basicInfo', 'scheduler', 'subpanels'],
    'panels' => [
        'basicInfo' => [
            'component' => 'MintPanelRecordDetails',
            'data' => [
                'sections' => [
                    'basic' => [
                        'title' => 'LBL_BASIC',
                        'fields' => [
                            ['name', 'status'],
                            ['type', 'assigned_user_name'],
                            ['date_start', 'date_end'],
                            ['description', 'repeat']
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
