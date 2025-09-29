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
                            [
                                'date_entered',
                                'date_modified',
                            ],
                            [
                                'created_by_name',
                                'modified_by_name',
                                'assigned_user_name',
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
