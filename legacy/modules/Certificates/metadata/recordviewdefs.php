<?php

$viewdefs['Certificates'] = [
    'order' => ['header', 'basicInfo', 'subpanels'],
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
        'basicInfo' => [
            'component' => 'MintPanelRecordDetails',
            'data' => [
                'sections' => [
                    'basic' => [
                        'title' => 'LBL_BASIC',
                        'fields' => [
                            [
                                [
                                    'name' => 'name',
                                    'required' => true,
                                ],
                                'attempts_number',
                                'pass_rate',
                            ],
                            [
                                'duration',
                                'description',
                            ],
                        ],
                    ],
                    'other' => [
                        'title' => 'LBL_RECORDVIEW_PANEL',
                        'collapsed' => true,
                        'fields' => [
                            [
                                'assigned_user_name',
                                'label' => 'LBL_ASSIGNED_TO_NAME',
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
        ],

        'subpanels' => [
            'component' => 'MintPanelSubpanels',
        ],
    ],
];
