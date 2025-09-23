<?php

// TODO - można uprościć
$viewdefs['EmployeeRoles'] = [
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
                    ['status'],
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
                            'options' => 'role_status',
                        ],
                        [
                            'name' => 'description',
                            'label' => 'LBL_DESCRIPTION',
                            'type' => 'text',
                        ],
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
