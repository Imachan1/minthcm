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
                        'task_grade',
                    ],
                ],
                'actions' => [
                    // [
                    //     'title' => 'LBL_DUPLICATE_BUTTON',
                    //     'icon' => 'mdi-content-copy',
                    //     'url' => ''
                    // ],
                    [
                        'title' => 'LBL_DELETE_BUTTON',
                        'icon' => 'mdi-trash-can-outline',
                        'click' => 'deleteBean',
                    ],
                    // [
                    //     'title' => 'LBL_DUP_MERGE',
                    //     'icon' => 'mdi-magnify',
                    //     'url' => ''
                    // ],
                    [
                        'title' => 'LNK_VIEW_CHANGE_LOG',
                        'icon' => 'mdi-history',
                        'click' => 'showBeanChangeLog',
                    ],
                ],
            ],
        ],
        'basicInfo' => [
            'component' => 'MintPanelRecordDetails',
            'data' => [
                'fields' => [
                    [
                        'status',
                        'to_decision',
                        'work_start',
                    ],
                    [
                        'training_date',
                        'parent_name',
                        'recruitment_name',
                    ],
                    [
                        'start_date',
                        'recruitment_end_name',
                        'status_information',
                    ],
                    [
                        'source',
                        'referrer',
                        'task_grade',
                    ],
                    [
                        'entry_interview',
                    ],
                    [
                        [
                            'name' => 'date_entered',
                            'readonly' => true,
                        ],
                        [
                            'name' => 'date_modified',
                            'readonly' => true,
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
