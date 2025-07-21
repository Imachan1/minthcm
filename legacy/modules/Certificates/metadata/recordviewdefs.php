<?php

$viewdefs['Certificates'] = [
    'order' => ['header', 'basicInfo', 'other', 'subpanels'],
    'panels' => [
        'header' => [
            'component' => 'MintPanelRecordHeader',
            'data' => [
                'fields' => [
                    ['name'],
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
                        [
                            'name' => 'name',
                            'required' => true,
                        ],
                        'attempts_number',
                        'pass_rate',
                    ],
                    [
                        'duration',
                        'description',
                    ],
                ],
            ],
        ],
        'other' => [
            'component' => 'MintPanelRecordPanel',
            'title' => 'LBL_RECORDVIEW_PANEL',
            'data' => [
                'fields' => [
                    [
                        'assigned_user_name',
                        'label' => 'LBL_ASSIGNED_TO_NAME',
                    ],
                    [
                        'name' => 'date_entered',
                        'readonly' => true,
                    ],
                    [
                        'name' => 'date_modified',
                        'readonly' => true,
                    ],
                ],
            ],
        ],

        'subpanels' => [
            'component' => 'MintPanelSubpanels',
        ],
    ],
];
