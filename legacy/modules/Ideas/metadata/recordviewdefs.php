<?php

$viewdefs['Ideas'] = [
    'clientDependencies' => [
        'modules/Ideas/js/edit.js',
    ],
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
                                ],
                                [
                                    'name' => 'status',
                                    'label' => 'LBL_STATUS',
                                    'type' => 'enum',
                                    'options' => 'idea_status_list',
                                ],
                                'description',
                            ],
                            [
                                [
                                    'name' => 'assigned_user_name',
                                    'label' => 'LBL_ASSIGNED_TO',
                                    'type' => 'relate',
                                    'module' => 'Users',
                                    'id_name' => 'assigned_user_id',
                                ],
                                [
                                    'name' => 'user_name',
                                    'label' => 'LBL_USER_NAME',
                                    'type' => 'relate',
                                    'module' => 'Users',
                                    'id_name' => 'user_id',
                                ],
                                'explanation',
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
                                    'name' => 'created_by_name',
                                    'readonly' => true,
                                ],
                                [
                                    'name' => 'modified_by_name',
                                    'readonly' => true,
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
