<?php

$viewdefs['TermsOfEmployment'] = [
    'order' => ['header', 'contract', 'subpanels'],
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
                'sections' => [
                    'basic' => [
                        'title' => 'LBL_BASIC',
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
                            ],
                            [
                                'description',
                            ],
                        ],
                    ],
                    'salary' => [
                        'title' => 'LBL_PANEL_SALARY',
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
            ],
        ],
        'subpanels' => [
            'component' => 'MintPanelSubpanels',
        ],
    ],
];
