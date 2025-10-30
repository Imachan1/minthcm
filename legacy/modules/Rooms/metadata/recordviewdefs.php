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
                    'Duplicate',
                ],
            ],
        ],
        'basicInfo' => [
            'component' => 'MintPanelRecordDetails',
            'data' => [
                'sections' => [
                    'basic' => [
                        'title' => 'LBL_BASIC',
                        'fields' => [
                            [
                                'name',
                                'assigned_user_name',
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
            ],
        ],
        'subpanels' => [
            'component' => 'MintPanelSubpanels',
        ],
    ],
];
