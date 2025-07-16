<?php

$viewdefs['SalaryRanges'] = [
    'order' => ['header', 'basicInfo', 'ranges', 'other', 'subpanels'],
    'panels' => [
        'header' => [
            'component' => 'MintPanelRecordHeader',
            'data' => [
                'fields' => [
                    [
                        ['name' => 'name'],
                        ['name' => 'position_name'],
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
                        ['name' => 'start_date'],
                        ['name' => 'end_date'],
                    ],
                    [
                        ['name' => 'currency_id'],
                    ],
                ],
            ],
        ],
        'ranges' => [
            'component' => 'MintPanelRecordPanel',
            'title' => 'LBL_SALARY_RANGES',
            'data' => [
                'fields' => [
                    [
                        ['name' => 'gross_value_from'],
                        ['name' => 'gross_value_to'],
                    ],
                    [
                        ['name' => 'net_value_from'],
                        ['name' => 'net_value_to'],
                    ],
                    [
                        ['name' => 'employer_costs_from'],
                        ['name' => 'employer_costs_to'],
                    ],
                ],
            ],
        ],
        'other' => [
            'component' => 'MintPanelRecordPanel',
            'title' => 'LBL_OTHER',
            'data' => [
                'fields' => [
                    [
                        ['name' => 'date_entered', 'readonly' => true],
                        ['name' => 'date_modified', 'readonly' => true],
                    ],
                    [
                        ['name' => 'assigned_user_name'],
                        ['name' => 'created_by_name', 'readonly' => true],
                        ['name' => 'modified_by_name', 'readonly' => true],
                    ],
                ],
            ],
        ],
        'subpanels' => [
            'component' => 'MintPanelSubpanels',
        ],
    ],
];
