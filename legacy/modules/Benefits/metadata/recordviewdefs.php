<?php

$viewdefs['Benefits'] = [
    'order' => ['header', 'basicInfo', 'subpanels'],
    'panels' => [
        'header' => [
            'component' => 'MintPanelRecordHeader',
            'data' => [
                'fields' => [
                    ['name'],
                ],
                'actions' => [
                    'Audit',
                    'Delete',
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
                            ['name'],
                            ['description'],
                        ],
                    ],
                    'other' => [
                        'title' => 'LBL_OTHER',
                        'collapsed' => true,
                        'fields' => [
                            [
                                ['name' => 'assigned_user_name'],
                                ['name' => 'date_entered', 'readonly' => true],
                                ['name' => 'date_modified', 'readonly' => true],
                            ],
                        ],
                    ],
                ],

            ],
        ],
        'subpanels' => [
            'component' => 'MintPanelSubpanels',
        ],
    ],
];
