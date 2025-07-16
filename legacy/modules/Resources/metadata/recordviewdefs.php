<?php

$viewdefs['Resources'] = [
    'order' => ['header', 'basicInfo', 'other', 'subpanels'],
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
        ],
        'other' => [
            'component' => 'MintPanelRecordPanel',
            'title' => 'LBL_DETAILVIEW_PANEL1',
            'data' => [
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
        'subpanels' => [
            'component' => 'MintPanelSubpanels',
            'title' => 'LBL_SUBPANELS',
        ],
    ],
];
