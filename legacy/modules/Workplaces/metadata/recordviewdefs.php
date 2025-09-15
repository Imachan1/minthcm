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
                            'filters' => [
                                [
                                    'field' => 'availability',
                                    'operator' => 'equal',
                                    'value' => 'active',
                                    'editable' => false,
                                ],
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
