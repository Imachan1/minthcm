<?php

$viewdefs['Costs'] = [
    'order' => ['header', 'contactInfo', 'subpanels'],
    'panels' => [
        'header' => [
            'component' => 'MintPanelRecordHeader',
            'data' => [
                'fields' => [
                    ['type'],
                ],
                'actions' => [
                    'Audit',
                    'Delete',
                ],
            ],
        ],
        'contactInfo' => [
            'component' => 'MintPanelRecordDetails',
            'title' => 'LBL_CONTACT_INFORMATION',
            'data' => [
                'sections' => [
                    'basic' => [
                        'title' => 'LBL_BASIC',
                        'fields' => [
                            ['type', 'delegation_name'],
                            [
                                'accommodation_no',
                            ],
                            [
                                'type_of_meal',
                            ],
                            [
                                'transportation_name',
                            ],
                            [
                                'cost_amount',
                                'currency_id',
                                'cost_date',
                            ],
                            [
                                'cost_city',
                                'description',
                            ],
                        ],
                    ],
                    'other' => [
                        'title' => 'LBL_OTHER',
                        'collapsed' => true,
                        'fields' => [
                            ['assigned_user_name', 'date_entered', 'date_modified'],
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
