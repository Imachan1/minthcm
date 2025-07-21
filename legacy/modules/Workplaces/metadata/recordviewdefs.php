<?php

$viewdefs['Workplaces'] = [
    'order' => ['header', 'basicInfo', 'subpanels'],
    'panels' => [
        'header' => [
            'component' => 'MintPanelRecordHeader',
            'data' => [
                'fields' => [
                    [
                        'availability',
                        'room_name',
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
                        'mode',
                    ],
                    [
                        'availability',
                        [
                            'name' => 'room_name',
                            'label' => 'LBL_RELATIONSHIP_ROOM_NAME',
                            'displayParams' => [
                                'initial_filter' => '&availability_advanced=active',
                            ],
                        ],
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
