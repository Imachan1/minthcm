<?php

$viewdefs['Rooms'] = [
    'order' => ['basicInfo', 'subpanels'],
    'panels' => [
        'basicInfo' => [
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
                                'name',
                                'assigned_user_name',
                            ],
                            [
                                [
                                    'name' => 'security_group_name',
                                    'filters' => [
                                        [
                                            'field' => 'group_type',
                                            'operator' => 'equal',
                                            'value' => 'business_unit',
                                            'editable' => false,
                                        ],
                                    ],
                                ],
                                'room_surface',
                            ],
                            [
                                'room_plan',
                                'number_of_seats',
                            ],
                            [
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
