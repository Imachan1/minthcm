<?php

$viewdefs['WorkSchedules'] = [
    'order' => ['header', 'basicInfo', 'recurrence', 'subpanels'],
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
                'sections' => [
                    'basic' => [
                        'title' => 'LBL_BASIC',
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
                    'other' => [
                        'title' => 'LBL_OTHER',
                        'collapsed' => true,
                        'fields' => [
                            [
                                ['name' => 'date_entered', 'readonly' => true],
                                ['name' => 'date_modified', 'readonly' => true],
                            ],
                            [
                                ['name' => 'assigned_user_name'],
                                ['name' => 'created_by_name', 'readonly' => true],
                                ['name' => 'modified_by_name', 'readonly' => true],
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
