<?php

$viewdefs['Documents'] = [
    'order' => ['header', 'documentInfo', 'subpanels'],
    'panels' => [
        'header' => [
            'component' => 'MintPanelRecordHeader',
            'data' => [
                'fields' => [
                    [
                        [
                            'name' => 'filename',
                            'type' => 'file',
                            'displayParams' => [
                                'onchangeSetFileNameTo' => 'document_name',
                            ],
                        ],
                        'status_id',
                        'revision',
                    ],
                ],
                'actions' => [
                    'Audit',
                    'Delete',
                ],
            ],
        ],
        'documentInfo' => [
            'component' => 'MintPanelRecordDetails',
            'data' => [
                'fields' => [
                    [
                        'document_name',
                        'filename',
                        'status_id',
                    ],
                    [
                        'active_date',
                        'exp_date',
                        'revision',
                    ],
                    [
                        'template_type',
                        'is_template',
                    ],
                    [
                        'category_id',
                        'subcategory_id',
                        'description',
                    ],
                    [
                        'related_doc_name',
                        'related_doc_rev_number',
                    ],
                    [
                        'date_entered',
                        'date_modified',
                        'assigned_user_name',
                    ],
                ],
            ],
        ],
        'subpanels' => [
            'component' => 'MintPanelSubpanels',
        ],
    ],
];
