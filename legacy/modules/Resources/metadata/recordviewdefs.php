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
                                'assigned_user_name',
                            ],
                        ],
                    ],
                    'other' => [
                        'title' => 'LBL_DETAILVIEW_PANEL1',
                        'collapsed' => true,
                        'fields' => [
                            [
                                'date_entered',
                                'date_modified',
                            ],
                            [
                                'created_by_name',
                                'modified_by_name',
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
