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
                        'icon' => 'mdi-history',
                    ],
                ],
            ],
        ],

        'mainPanel' => [
            'component' => 'MintPanelRecordDetails',
            'data' => [
                'sections' => [
                    'basic' => [
                        'title' => 'LBL_BASIC',
                        'fields' => [
                            [
                                [
                                    'name' => 'name',
                                    'label' => 'LBL_NAME',
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
