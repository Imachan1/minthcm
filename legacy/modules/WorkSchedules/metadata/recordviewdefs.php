<?php

$viewdefs['WorkSchedules'] = [
    'order' => ['header', 'basicInfo', 'recurrence', 'subpanels'],
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
                    [
                        'spent_time',
                        'spent_time_settlement',
                        'supervisor_acceptance',
                    ],
                ],
                'actions' => [
                    'Audit',
                    'Delete',
                    'CloseWorkSchedule',
                    'AcceptWorkSchedule',
                    'UndoAcceptWorkSchedule',
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
                                ['name' => 'assigned_user_name'],
                                ['name' => 'type'],
                                ['name' => 'status'],
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
                                ['name' => 'delegation_name'],
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
