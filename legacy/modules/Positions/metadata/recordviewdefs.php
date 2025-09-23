<?php

$viewdefs['Positions'] = [
    'order' => ['header', 'mainPanel', 'subpanels'],
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
                            'options' => 'position_status',
                        ],
                        'assigned_user_name',
                    ],
                    [
                        'securitygroup_leader_name',
                        'positions_supervision_name',
                        'description',
                    ],
                    [
                        'offboardingtemplate_name',
                        'onboardingtemplate_name',
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
