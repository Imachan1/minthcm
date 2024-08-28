<?php

$viewdefs['Employees'] = [
    'order' => ['header', 'basicInfo', 'subpanels'],
    'panels' => [
        'header' => [
            'component' => 'MintPanelEmployeeRecordHeader',
            'data' => [
                'fields' => [
                    [
                        'position_name',
                        'phone_home',
                        [
                            'name' => 'email1',
                            'type' => 'email',
                        ],
                    ],
                ],
                'actions' => [
                    // [
                    //     'title' => 'LBL_DUPLICATE_BUTTON_LABEL',
                    //     'icon' => 'mdi-content-copy',
                    //     'url' => ''
                    // ],
                    [
                        'title' => 'LBL_DELETE_BUTTON_LABEL',
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
                    // CREATE APPRAISAL BUTTON
                ],
            ],
        ],
        'basicInfo' => [
            'component' => 'MintPanelRecordDetails',
            'data' => [
                'fields' => [
                    [
                        'first_name',
                        'last_name',
                        [
                            'name' => 'email1',
                            'type' => 'email',
                        ],
                    ],
                    [
                        'phone_home',
                        'phone_other',
                        'phone_fax',
                    ],
                    [
                        'employee_status',
                        'position_name',
                        'reports_to_name',
                    ],
                    [
                        'securitygroup_name',
                        'description',
                        [
                            'name' => 'summary_points',
                            'readonly' => true,
                        ],
                    ],
                    [
                        [
                            'name' => 'current_points',
                            'readonly' => true,
                        ],
                        [
                            'name' => 'spent_points',
                            'readonly' => true,
                        ],
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
                    ]
                ],
            ],
        ],
        'subpanels' => [
            'component' => 'MintPanelSubpanels',
        ],
    ],
];
