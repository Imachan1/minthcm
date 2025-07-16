<?php

$viewdefs['Rooms'] = [
    'order' => ['header', 'basicInfo', 'subpanels'],
    'panels' => [
        'header' => [
            'component' => 'MintPanelRecordHeader',
            'data' => [
                'fields' => [
                    [
                        'availability',
                    ],
                ],
                'actions' => [
                    'Audit',
                    'Delete',
                ],
            ],
        ],
        'basicInfo' => [
            'component' => 'MintPanelRecordDetails',
            'data' => [
                'fields' => [
                    [
                        'name',
                        'assigned_user_name',
                        [
                            'name' => 'security_group_name',
                            'label' => 'LBL_RELATIONSHIP_SECURITY_GROUP_NAME',
                            'displayParams' => [
                                'initial_filter' => '&group_type=business_unit',
                            ],
                        ],
                    ],
                    [
                        'availability',
                        'room_surface',
                        'room_plan',
                    ],
                    [
                        'number_of_seats',
                        'description',
                    ],
                    [
                        'date_entered',
                        'date_modified',
                    ],
                    [
                        'created_by_name',
                        'modified_by_name',
                    ],
                ],
            ],
        ],
        'subpanels' => [
            'component' => 'MintPanelSubpanels',
        ],
    ],
];
