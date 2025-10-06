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
                'sections' => [
                    'basic' => [
                        'title' => 'LBL_BASIC',
                        'fields' => [
                            [
                                'name', 
                                'status', 
                                'priority'
                            ],
                            [
                                ['name' => 'date_start', 'readonly' => true], 
                                ['name' => 'date_due', 'readonly' => true]
                            ],
                            [
                                'parent_name', 
                                'description', 
                                ''
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
