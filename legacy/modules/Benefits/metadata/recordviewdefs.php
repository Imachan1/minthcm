<?php

$viewdefs['Benefits'] = [
    'order' => ['basicInfo', 'subpanels'],
    'panels' => [
        'basicInfo' => [
            'component' => 'MintPanelRecordDetails',
            'data' => [
                'actions' => [
                    'Audit',
                    'Delete',
                    'Duplicate',
                ],
                'sections' => [
                    'basic' => [
                        'title' => 'LBL_BASIC',
                        'fields' => [
                            ['name'],
                            ['description'],
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
