<?php

$viewdefs['Delegations'] = [
    'order' => ['header', 'basicInfo', 'subpanels'],
    'panels' => [
        'header' => [
            'component' => 'MintPanelRecordHeader',
            'data' => [
                'fields' => [
                    [
                        'name',
                        'assigned_user_name',
                        'owner',
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
                                'delegation_locale_name',
                                'currency_id',
                                'assured_number_of_breakfasts',
                            ],
                            [
                                'start_date',
                                'end_date',
                                'assured_number_of_dinners',
                            ],
                            [
                                'purpose',
                                'obtained_sum',
                                'assured_number_of_suppers',
                            ],
                            [
                                'description',
                                '',
                                'assured_number_of_accommodations',
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'subpanels' => [
            'component' => 'MintPanelSubpanels',
            'title' => 'LBL_SUBPANELS',
        ],
    ],
];
