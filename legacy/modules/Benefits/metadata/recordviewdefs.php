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
                            ['name', 'assigned_user_name'],
                            ['description'],
                        ],
                    ],
                    'other' => [
                        'title' => 'LBL_OTHER',
                        'collapsed' => true,
                        'fields' => [
                            [
                                ['name' => 'date_entered', 'readonly' => true],
                                ['name' => 'date_modified', 'readonly' => true],
                            ],
                            [
                                ['name' => 'assigned_user_name'],
                                ['name' => 'created_by_name', 'readonly' => true],
                                ['name' => 'modified_by_name', 'readonly' => true],
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
