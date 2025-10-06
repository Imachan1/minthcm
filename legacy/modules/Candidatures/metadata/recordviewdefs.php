<?php

$viewdefs['Candidatures'] = [
    'order' => ['header', 'basicInfo', 'files', 'subpanels'],
    'panels' => [
        'header' => [
            'component' => 'MintPanelRecordHeader',
            'data' => [
                'fields' => [
                    [
                        'status',
                        'scoring',
                        'task_grade',
                    ],
                ],
                'actions' => [
                    'Audit',
                    'Delete',
                    'ConvertToEmployee',
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
                    'd1' => [
                        'title' => 'LBL_RECORDVIEW_PANEL5',
                        'fields' => [
                            [
                                'employment_form',
                                'dg_amount',
                                'currency_name',
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
                    'd2' => [
                        'title' => 'LBL_RECORDVIEW_PANEL4',
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
                    'other' => [
                        'title' => 'LBL_PANEL_ASSIGNMENT',
                        'collapsed' => true,
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
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'files' => [
            'component' => 'MintPanelFiles',
        ],
        'subpanels' => [
            'component' => 'MintPanelSubpanels',
        ],
    ],
];
