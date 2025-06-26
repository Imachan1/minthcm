<?php

$viewdefs['OnboardingTemplates'] = [
    'order' => [
        'header',
        'mainPanel',
        'subpanels',
    ],
    'panels' => [

        'header' => [
            'component' => 'MintPanelRecordHeader',
            'data' => [
                'fields' => [
                    ['name'],
                ],
                'actions' => [
                    'Audit',
                    'Delete',
                    [
                        'title' => 'LBL_GENERATE_BUTTON',
                        'icon' =>'mdi-history',
                    ],
                ],
            ],
        ],

        'mainPanel' => [
            'component' => 'MintPanelRecordDetails',
            'data' => [
                'fields' => [
                    [
                        [
                            'name'  => 'name',
                            'label' => 'LBL_NAME',
                        ],
                        [
                            'name'     => 'assigned_user_name',
                            'label'    => 'LBL_ASSIGNED_TO',
                        ],
                    ],
                    [
                        [
                            'name'  => 'description',
                            'label' => 'LBL_DESCRIPTION',
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
                                            
                    ],
                    [
                        [
                            'name' => 'modified_by_name',
                            'readonly' => true,
                        ],                   
                        [
                            'name' => 'created_by_name',
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