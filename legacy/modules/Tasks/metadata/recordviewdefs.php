<?php

$viewdefs['Tasks'] = [
    'order' => ['overview', 'checklist', 'subpanels'],
    'panels' => [
        'overview' => [
            'component' => 'MintPanelRecordDetails',
            'data' => [
                'actions' => [
                    'Audit',
                    'Delete',
                    'Duplicate',
                ],
                'sections' => [
                    'basic' => [
                        'title' => 'LBL_BASIC',
                        'fields' => [
                            [
                                'name',
                                'status',
                            ],
                            [
                                'priority',
                                'parent_name',
                            ],
                            [
                                'date_start', 'date_due',
                            ],
                            ['description'],
                        ],
                    ],
                ],
            ],
        ],
        'checklist' => [
            'component' => 'MintPanelChecklist',
            'title' => 'LBL_CHECKLIST',
        ],        'subpanels' => [
            'component' => 'MintPanelSubpanels',
        ],
    ],
];
