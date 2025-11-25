<?php

$viewdefs['Notes'] = [
    'order' => ['overview', 'subpanels'],
    'panels' => [
        'overview' => [
            'component' => 'MintPanelRecordDetails',
            'title' => 'LBL_NOTE_INFORMATION',
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
