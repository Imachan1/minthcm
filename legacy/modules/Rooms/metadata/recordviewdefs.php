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
                'sections' => [
                    'basic' => [
                        'title' => 'LBL_BASIC',
                        'fields' => [
                            [
                                'name',
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
