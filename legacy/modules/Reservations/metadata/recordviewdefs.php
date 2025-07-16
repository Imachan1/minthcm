<?php

$viewdefs['Reservations'] = [
    'order' => ['header', 'basicInfo', 'd1', 'other', 'subpanels'],
    'panels' => [
        'header' => [
            'component' => 'MintPanelRecordHeader',
            'data' => [
                'fields' => [
                    [
                        'name',
                        'status',
                        'resource_name',
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
                        'starting_date',
                        'ending_date',
                    ],
                    [
                        'resource_name',
                        'delegation_name',

                    ],
                    [
                        'employee_name',
                        'assigned_user_name',

                    ],
                    [
                        'description',
                    ],
                ],
            ],
        ],
        'd1' => [
            'component' => 'MintPanelRecordPanel',
            'title' => 'LBL_RECORDVIEW_PANEL1',
            'data' => [
                'fields' => [
                    [
                        'parent_name',
                        '',
                    ],
                ],
            ],
        ],
        'subpanels' => [
            'component' => 'MintPanelSubpanels',
        ],
    ],
];
