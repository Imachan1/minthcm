<?php

$viewdefs['OffboardingTemplates'] = [
    'order' => [
        'mainPanel',
        'subpanels',
    ],
    'panels' => [
        'mainPanel' => [
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
                                [
                                    'name' => 'name',
                                    'label' => 'LBL_NAME',
                                ],
                                [
                                    'name' => 'assigned_user_name',
                                    'label' => 'LBL_ASSIGNED_TO',
                                ],
                            ],
                            [
                                [
                                    'name' => 'description',
                                    'label' => 'LBL_DESCRIPTION',
                                ],
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
