<?php

$viewdefs['WorkSchedules'] = [
    'order' => ['header', 'basicInfo', 'other', 'recurrence', 'subpanels'],
    'panels' => [
        'header' => [
            'component' => 'MintPanelRecordHeader',
            'data' => [
                'fields' => [
                    [
                        ['name' => 'name'],
                        ['name' => 'status'],
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
                        ['name' => 'name'],
                        ['name' => 'status'],
                    ],
                    [
                        ['name' => 'assigned_user_name'],
                        ['name' => 'type'],
                    ],
                    [
                        ['name' => 'date_start'],
                        ['name' => 'date_end'],
                    ],
                    [
                        ['name' => 'duration_hours'],
                        ['name' => 'duration_minutes'],
                    ],
                    [
                        ['name' => 'workplace_name'],
                    ],
                    [
                        ['name' => 'description'],
                    ],
                    [
                        ['name' => 'delegation_duration'],
                        ['name' => 'occasional_leave_type'],
                        ['name' => 'comments'],
                    ],
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
