<?php

$viewdefs['TermsOfEmployment'] = [
    'order' => ['header', 'contract', 'salary', 'subpanels'],
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
        'contract' => [
            'component' => 'MintPanelRecordDetails',
            'data' => [
                'fields' => [
                    [
                        'name',
                        'contract_name',
                        'date_of_signing',
                    ],
                    [
                        'term_starting_date',
                        'term_ending_date',
                        'position_name',
                    ],
                    [
                        'employee_name',
                        'assigned_user_name',
                    ],
                    [
                        [
                            'name' => 'date_entered',
                            'readonly' => true,
                        ],
                        [
                            'name' => 'date_modified',
                            'readonly' => true,
                        ],
                    ],
                    [
                        'description',
                    ],
                ],
            ],
        ],
        'salary' => [
            'component' => 'MintPanelRecordPanel',
            'title' => 'LBL_PANEL_SALARY',
            'data' => [
                'fields' => [
                    [
                        'gross',
                        'net',
                    ],
                    [
                        'employer_cost',
                        [
                            'name' => 'currency_id',
                            'type' => 'relate',
                            'module' => 'Currencies',
                            'id_name' => 'currency_id',
                            'rname' => 'name',
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
