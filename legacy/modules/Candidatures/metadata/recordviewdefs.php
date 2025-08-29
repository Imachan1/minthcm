<?php

$viewdefs['Candidatures'] = [
    'order' => ['header', 'basicInfo', 'd1', 'd2', 'other', 'subpanels'],
    'panels' => [
        'header' => [
            'component' => 'MintPanelRecordHeader',
            'data' => [
                'fields' => [
                    [
                        'name',
                        'status',
                        'scoring',
                    ], 
                ],
                'actions' => [
                    'Audit',
                    'Delete'
                    // [
                    //     'title' => 'LBL_DUPLICATE_BUTTON',
                    //     'icon' => 'mdi-content-copy',
                    //     'url' => ''
                    // ],
                    // [
                    //     'title' => 'LBL_DUP_MERGE',
                    //     'icon' => 'mdi-magnify',
                    //     'url' => ''
                    // ],
                ],
            ],
        ],
        'basicInfo' => [
            'component' => 'MintPanelRecordDetails',
            'data' => [
                'fields' => [
                    [
                        'status',
                        'status_information',
                        'start_date',
                    ],
                    [
                        'reason_for_rejection',
                    ],
                    [
                        'work_start',
                        'training_date',
                    ],
                    [
                        'to_decision',
                        'recruitment_name',
                        'recruitment_end_name',
                    ],
                    [
                        'parent_name',
                        'entry_interview',
                    ],
                    [
                        'source',
                        'task_grade',
                        'scoring',
                    ],
                    [
                        'description',
                    ],
                ],
            ],
        ],
        'd1' => [
            'component' => 'MintPanelRecordPanel',
            'title' => 'LBL_RECORDVIEW_PANEL5',
            'data' => [
                'fields' => [
                    [
                        'employment_form',
                        'dg_amount',
                        'currency_id',
                    ],
                    [
                        'net_amount',
                        'gross_amount',
                    ],
                    [
                        'notice',
                    ],
                ],
            ],
        ],
        'd2' => [
            'component' => 'MintPanelRecordPanel',
            'title' => 'LBL_RECORDVIEW_PANEL4',
            'data' => [
                'fields' => [
                    [
                        'final_employment_form',
                        'salary_net',
                    ],
                    [
                        'notice_final_expectations',
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
                        'employee_name',
                        [
                            'name' => 'date_entered',
                            'readonly' => true,
                        ],
                        [
                            'name' => 'date_modified',
                            'readonly' => true,
                        ],
                    ],
                    [
                        'assigned_user_name',
                        [
                            'name' => 'created_by_name',
                            'readonly' => true,
                        ],
                        [
                            'name' => 'modified_by_name',
                            'readonly' => true,
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
