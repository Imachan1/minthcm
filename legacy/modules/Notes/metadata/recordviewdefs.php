<?php

$viewdefs['Notes'] = [
    'order' => ['header', 'overview', 'subpanels'],
    'panels' => [
        'header' => [
            'component' => 'MintPanelRecordHeader',
            'data' => [
                'fields' => [
                    [
                        'filename',
                        'assigned_user_name',
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
            'title' => 'LBL_NOTE_INFORMATION',
            'data' => [
                'sections' => [
                    'basic' => [
                        'title' => 'LBL_BASIC',
                        'fields' => [
                            [
                                'name',
                                'filename',
                            ],
                            [
                                'parent_name',
                                'description',
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
