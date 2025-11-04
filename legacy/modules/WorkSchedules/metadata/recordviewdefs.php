<?php

$viewdefs['WorkSchedules'] = [
    'order' => ['basicInfo', 'recurrence', 'subpanels'],
    'panels' => [
        'basicInfo' => [
            'component' => 'MintPanelRecordDetails',
            'data' => [
                'actions' => [
                    'Audit',
                    'Delete',
                    'Duplicate',
                ],
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
                            ],
                            [
                                ['name' => 'comments'],
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
