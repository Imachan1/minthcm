<?php

$viewdefs['Allocations'] = [
    'order' => ['header', 'basicInfo', 'subpanels'],
    'panels' => [
        'header' => [
            'component' => 'MintPanelRecordHeader',
            'data' => [
                'fields' => [
                    [
                        'mode',
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
                        'assigned_user_name',
                        'mode',
                        [
                            'name' => 'workplace_name',
                            'label' => 'LBL_RELATIONSHIP_WORKPLACES',
                            'filters' => [
                                [
                                    'field' => 'availability',
                                    'operator' => 'equal',
                                    'value' => 'active',
                                    'editable' => false, 
                                ],
                            ],
                        ],
                    ],
                    [
                        'date_from',
                        'date_to',
                        'description',
                    ],
                    [
                        'date_entered',
                        'date_modified',
                    ],
                    [
                        'created_by_name',
                        'modified_by_name',
                    ],
                ],
            ],
        ],
        'subpanels' => [
            'component' => 'MintPanelSubpanels',
        ],
    ],
];
