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
                        'phone_work',
                        [
                            'name' => 'email1',
                            'type' => 'email',
                        ],
                    ],
                ],
                'actions' => [
                    [
                        'title' => 'LBL_DELETE_BUTTON_LABEL',
                        'icon' => 'mdi-trash-can-outline',
                        'click' => 'deleteBean',
                    ],
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
                        'first_name',
                        'last_name',
                        [
                            'name' => 'email1',
                            'type' => 'email',
                        ],
                    ],
                    [
                        'phone_mobile',
                        'phone_work',
                        'phone_other',
                    ],
                    [
                        'employee_status',
                        [
                            'name' => 'birthdate',
                            'type' => 'age',
                        ],
                    ], 
                    [
                        'position_name',
                        'securitygroup_name',
                        'reports_to_name',
                    ],
                    [
                        'messenger_type',
                        'messenger_id',
                    ],
                    [
                        'primary_address_street',
                        'primary_address_city',
                        'primary_address_state',
                    ],
                    [
                        'primary_address_postalcode',
                        'primary_address_country',
                    ],
                    [
                        'leave_days_in_a_year',
                        'remaining_leave_days',
                    ],
                    [
                        [
                            'name' => 'summary_points',
                            'readonly' => true,
                        ],
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
                        'description',
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
