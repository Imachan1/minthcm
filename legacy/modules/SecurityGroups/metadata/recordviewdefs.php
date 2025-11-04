<?php

$viewdefs['SecurityGroups'] = [
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
                                    'type' => 'varchar',
                                    'required' => true,
                                ],
                                'group_type',
                            ],
                            [
                                [
                                    'name' => 'parent_name',
                                    'label' => 'LBL_MEMBER_OF',
                                    'type' => 'relate',
                                    'module' => 'SecurityGroups',
                                ],
                                [
                                    'name' => 'current_manager_name',
                                    'label' => 'LBL_CURRENT_MANAGER_NAME',
                                    'type' => 'relate',
                                    'module' => 'Users',
                                ],
                            ],
                            [
                                [
                                    'name' => 'position_leader_name',
                                    'label' => 'LBL_POSITION_LEADER_NAME',
                                    'type' => 'relate',
                                    'module' => 'Positions',
                                ],
                                [
                                    'name' => 'assigned_user_name',
                                    'label' => 'LBL_ASSIGNED_TO',
                                    'type' => 'relate',
                                    'module' => 'Users',
                                    'id_name' => 'assigned_user_id',
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
