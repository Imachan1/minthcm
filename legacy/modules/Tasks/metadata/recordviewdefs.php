<?php

$viewdefs['Tasks'] = [
    'order' => ['header', 'overview', 'checklist', 'subpanels'],
    'panels' => [
        'header' => [
            'component' => 'MintPanelRecordHeader',
            'data' => [
                'fields' => [
                    [
                        'status',
                        'date_start',
                        'date_due',
                    ],
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
                            ['name', 'status', 'priority'],
                            [
                                'date_start',
                                'date_due',
                                'parent_name',
                            ],
                            ['assigned_user_name', 'description'],
                            [

                                'date_entered',
                                'date_modified',
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'checklist' => [
            'component' => 'MintPanelChecklist',
            'title' => 'LBL_CHECKLIST',
        ],
        'subpanels' => [
            'component' => 'MintPanelSubpanels',
        ],
    ],
];
