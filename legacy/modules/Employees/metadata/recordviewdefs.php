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
                'sections' => [
                    'basic' => [
                        'title' => 'LBL_BASIC',
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
                                [
                                    'name' => 'photo',
                                    'type' => 'file',
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
                                [
                                    'name' => 'primary_address',
                                    'type' => 'fieldset',
                                    'label' => 'LBL_PRIMARY_ADDRESS',
                                    'properties' => [
                                        'fields' => [
                                            'primary_address_street',
                                            'primary_address_city',
                                            'primary_address_state',
                                            'primary_address_postalcode',
                                            'primary_address_country',
                                        ],
                                        'separator' => ', ',
                                    ],
                                ],
                                'description',
                            ],
                            ['created_by_name', 'modified_by_name'],
                            ['date_entered', 'date_modified'],
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
