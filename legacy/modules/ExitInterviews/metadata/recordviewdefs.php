<?php

$viewdefs['ExitInterviews'] = [
    'order' => ['header', 'details', 'subpanels'],
    'panels' => [
        'header' => [
            'component' => 'MintPanelRecordHeader',
            'data' => [
                'fields' => [
                    [
                        'employee_name',
                        'status',
                    ],
                ],
                'actions' => [
                    'Audit',
                    'Delete',
                ],
            ],
        ],
        'details' => [
            'component' => 'MintPanelRecordDetails',
            'data' => [
                'fields' => [
                    [
                        'name',
                        'employee_name',
                        'status',
                    ],
                    [
                        'date_start',
                        'date_end',
                        'offboarding_name',
                    ],
                    [
                        'description',
                    ],
                    [
                        'date_entered',
                        'date_modified',
                    ],
                    [
                        'created_by_name',
                        'modified_by_name',
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
