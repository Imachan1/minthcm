<?php

$viewdefs['ProspectLists'] = [
    'order' => ['header', 'basicInfo', 'subpanels'],
    'panels' => [
        'header' => [
            'component' => 'MintPanelRecordHeader',
            'data' => [
                'fields' => [
                    ['list_type', 'entry_count'],
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
                        'list_type',
                        'domain_name',
                    ],
                    [
                        'automatic_update',
                        'kreport_name',
                        'description',
                    ],
                    [
                        'date_entered',
                        'date_modified',
                        'assigned_user_name',
                    ],
                ],
            ],
        ],
        'subpanels' => [
            'component' => 'MintPanelSubpanels',
        ],
    ],
];
