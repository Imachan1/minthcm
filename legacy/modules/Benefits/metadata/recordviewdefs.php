<?php

$viewdefs['Benefits'] = [
    'order' => ['header', 'basicInfo', 'relations', 'other', 'subpanels'],
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
                    ['name', 'assigned_user_name'],
                    ['description'],
                ],
            ],
        ],
        'other' => [
            'component' => 'MintPanelRecordPanel',
            'title' => 'LBL_OTHER',
            'data' => [
                'fields' => [
                    [
                        ['name' => 'date_entered', 'readonly' => true],
                        ['name' => 'date_modified', 'readonly' => true],
                    ],
                    [
                        ['name' => 'assigned_user_name'],
                    ],
                ],
            ],
        ],
        'subpanels' => [
            'component' => 'MintPanelSubpanels',
        ],
    ],
];
