<?php

$viewdefs['EmployeeCertificates'] = [
    'order' => ['header', 'basicInfo', 'userInfo', 'subpanels'],
    'panels' => [
        'header' => [
            'component' => 'MintPanelRecordHeader',
            'data' => [
                'fields' => [
                    ['certificate_name', 'status'],
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
                    ['status', 'start_date', 'end_date'],
                    ['attempts_number', 'points_scored',
                        [
                            'name' => 'certificate_name',
                            'label' => 'LBL_RELATIONSHIP_CERTIFICATE_NAME',
                        ],
                    ],
                    [
                        [
                            'name' => 'candidate_name',
                            'label' => 'LBL_RELATIONSHIP_CANDIDATE_NAME',
                        ],
                        'employee_name', 'name',
                    ],
                    ['description'],
                ],
            ],
        ],
        'userInfo' => [
            'component' => 'MintPanelRecordDetails',
            'data' => [
                'fields' => [
                    ['assigned_user_name'],
                ],
            ],
        ],
        'subpanels' => [
            'component' => 'MintPanelSubpanels',
        ],
    ],
];
