<?php 

$viewdefs['Candidates'] = [
    'order' => ['header', 'contactInfo', 'moreInfo', 'socials', 'assignment', 'subpanels'],
    'panels' => [
        'header' => [
            'component' => 'MintPanelRecordHeader',
            'data' => [
                'fields' => [
                    ['first_name', 'last_name', 'phone_mobile'],
                ],
                'actions' => [
                    'Audit',
                    'Delete',
                ],
            ],
        ],
        'contactInfo' => [
            'component' => 'MintPanelRecordDetails',
            'title'     => 'LBL_CONTACT_INFORMATION',
            'data'      => [
                'fields' => [
                    ['first_name', 'last_name', 'email1'],
                    ['birthdate', 'phone_mobile', 'recr_contact_agree', ],
                    [
                        [
                            'name' => 'primary_address',
                            'type' => 'fieldset',
                            'label' => 'LBL_PRIMARY_ADDRESS',
                            'properties' => [
                                'fields' => [
                                    'primary_address_street',
                                    'primary_address_city',
                                    'primary_address_state',
                                    'primary_address_postalcode',
                                    'primary_address_country',
                                ],
                                'separator' => ',',
                            ],
                        ],
                        [
                            'name' => 'alt_address',
                            'type' => 'fieldset',
                            'label' => 'LBL_ALT_ADDRESS',
                            'properties' => [
                                'fields' => [
                                    'alt_address_street',
                                    'alt_address_city',
                                    'alt_address_state',
                                    'alt_address_postalcode',
                                    'alt_address_country',
                                ],
                                'separator' => ',',
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'moreInfo' => [
            'component' => 'MintPanelRecordPanel',
            'title'     => 'LBL_SHOW_MORE_INFORMATION',
            'data'      => [
                'fields' => [
                    ['potential', 'relocation', 'description'], 
                ],
            ],
        ],
        'socials' => [
            'component' => 'MintPanelRecordPanel',
            'title'     => 'LBL_RECORDVIEW_PANEL1',
            'data'      => [
                'fields' => [
                    ['linkedin', 'github', 'facebook'],
                    ['skype', '', ''],
                ],
            ],
        ],
        'assignment' => [
            'component' => 'MintPanelRecordPanel',
            'title'     => 'LBL_RECORDVIEW_PANEL2',
            'data'      => [
                'fields' => [
                    ['assigned_user_name', 'created_by_name', 'modified_by_name'],
                ],
            ],
        ],
        'files' => [
            'component' => 'MintPanelFiles',
        ],
        'subpanels' => [
            'component' => 'MintPanelSubpanels',
        ],
    ],
];

?>
