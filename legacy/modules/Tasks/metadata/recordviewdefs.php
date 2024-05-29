<?php

$viewdefs['Tasks'] = [
    'order' => ['header', 'basicInfo', 'subpanels'],
    'panels' => [
        'header' => [
            'component' => 'MintPanelRecordHeader',
            'data' => [
                'fields' => [
                    [
                        'status',
                        'date_due',
                    ],
                ],
                'actions' => [
                    // [
                    //     'title' => 'LBL_DUPLICATE_BUTTON',
                    //     'icon' => 'mdi-content-copy',
                    //     'url' => ''
                    // ],
                    [
                        'title' => 'LBL_DELETE_BUTTON',
                        'icon' => 'mdi-trash-can-outline',
                        'click' => 'deleteBean',
                    ],
                    // [
                    //     'title' => 'LBL_DUP_MERGE',
                    //     'icon' => 'mdi-magnify',
                    //     'url' => ''
                    // ],
                    [
                        'title' => 'LNK_VIEW_CHANGE_LOG',
                        'icon' => 'mdi-history',
                        'click' => 'showBeanChangeLog',
                    ],
                ],
            ],
        ],
        'basicInfo' => [
            'component' => 'MintPanelRecordDetails',
            'data' => [
                'fields' => [
                    [
                        'name',
                        'status',
                        'parent_name',
                    ],
                    [
                        'date_start',
                        'date_due',
                        'contact_name',
                    ],
                    [
                        'priority',
                        'assigned_user_name'
                    ],
                    [
                        'description',
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
                ],
            ],
        ],
        'subpanels' => [
            'component' => 'MintPanelSubpanels',
        ],
    ],
];
