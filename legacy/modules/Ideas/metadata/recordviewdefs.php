<?php

$viewdefs['Ideas'] = [
    'clientDependencies' => [
        'modules/Ideas/js/edit.js',
    ],
    'order' => [
        'header',
        'mainPanel',
        'subpanels',
        'other',
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
                ],
            ],
        ],

        'subpanels' => [
            'component' => 'MintPanelSubpanels',
        ],
    ],
];
