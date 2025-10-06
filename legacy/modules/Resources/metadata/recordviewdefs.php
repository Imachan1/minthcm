<?php

$viewdefs['Resources'] = [
    'order' => ['header', 'basicInfo', 'subpanels'],
    'panels' => [
        'header' => [
            'component' => 'MintPanelRecordHeader',
            'data' => [
                'fields' => [
                    [
                        'name',
                        'status',
                        'type',
                    ],
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
                            [
                                'name',
                                'employee_name',
                                'type',
                            ],
                            [
                                'unavailable',
                                'description',
                            ],
                        ],
                    ],
                    'other' => [
                        'title' => 'LBL_DETAILVIEW_PANEL1',
                        'collapsed' => true,
                        'fields' => [
                            [
                                'assigned_user_name',
                                'date_entered',
                                'date_modified',
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'subpanels' => [
            'component' => 'MintPanelSubpanels',
            'title' => 'LBL_SUBPANELS',
        ],
    ],
];
