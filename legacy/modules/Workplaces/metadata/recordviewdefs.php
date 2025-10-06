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
                'sections' => [
                    'basic' => [
                        'title' => 'LBL_BASIC',
                        'fields' => [
                            [
                                'name',
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
