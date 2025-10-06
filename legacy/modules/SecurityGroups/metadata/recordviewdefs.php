<?php

$viewdefs['SecurityGroups'] = [
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
                    ['parent_name'],
                    ['position_leader_name'],
                ],
                'actions' => [
                    'Audit',
                    'Delete',
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
                                    'type' => 'varchar',
                                    'required' => true,
                                ],
                                'group_type',
                                [
                                    'name' => 'parent_name',
                                    'label' => 'LBL_MEMBER_OF',
                                    'type' => 'relate',
                                    'module' => 'SecurityGroups',
                                ],
                            ],
                            [
                                [
                                    'name' => 'current_manager_name',
                                    'label' => 'LBL_CURRENT_MANAGER_NAME',
                                    'type' => 'relate',
                                    'module' => 'Users',
                                ],
                                [
                                    'name' => 'position_leader_name',
                                    'label' => 'LBL_POSITION_LEADER_NAME',
                                    'type' => 'relate',
                                    'module' => 'Positions',
                                ],
                            ],
                            [
                                'noninheritable',
                                'description',
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
