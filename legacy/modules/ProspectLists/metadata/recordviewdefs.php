<?php

$viewdefs['ProspectLists'] = [
    'order' => ['header', 'basicInfo', 'subpanels'],
    'panels' => [
        'header' => [
            'component' => 'MintPanelRecordHeader',
            'data' => [
                'fields' => [
                    ['list_type', 'entry_count'],
                ],
                'actions' => [
                    'Audit',
                    'Delete',
                    'Duplicate',
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
                                'list_type',
                                'domain_name',
                            ],
                            [
                                'automatic_update',
                                'kreport_name',
                                'description',
                            ],
                            [
                                'date_entered',
                                'date_modified',
                            ],
                            [
                                'created_by_name',
                                'modified_by_name',
                                'assigned_user_name',
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
