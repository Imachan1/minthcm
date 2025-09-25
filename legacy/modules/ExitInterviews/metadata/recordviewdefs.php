<?php

$viewdefs['ExitInterviews'] = [
    'order' => ['header', 'details', 'subpanels'],
    'panels' => [
        'header' => [
            'component' => 'MintPanelRecordHeader',
            'data' => [
                'fields' => [
                    [
                        'status',
                        'employee_name',
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
                        'assigned_user_name',
                        'date_entered',
                        'date_modified',
                    ],
                ],
            ],
        ],
        'subpanels' => [
            'component' => 'MintPanelSubpanels',
        ],
    ],
];
