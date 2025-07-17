<?php

$viewdefs['Delegations'] = [
    'order' => ['header', 'basicInfo', 'other', 'subpanels'],
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
        // 'other' => [
        //     'component' => 'MintPanelRecordPanel',
        //     'title' => 'LBL_DETAILVIEW_PANEL1',
        //     'data' => [
        //         'fields' => [
        //             [
        //                 'date_entered',
        //                 'date_modified',
        //             ],
        //             [
        //                 'created_by_name',
        //                 'modified_by_name',
        //             ],
        //         ],
        //     ],
        // ],
        'subpanels' => [
            'component' => 'MintPanelSubpanels',
            'title' => 'LBL_SUBPANELS',
        ],
    ],
];
