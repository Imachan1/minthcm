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
                    ['primary_address_street', 'alt_address_street', 'birthdate'],
                    ['primary_address_city', 'alt_address_city', 'phone_mobile'],
                    ['primary_address_state', 'alt_address_state', 'recr_contact_agree'],
                    ['primary_address_postalcode', 'alt_address_postalcode', ''],
                    ['primary_address_country', 'alt_address_country', ''],
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
        'subpanels' => [
            'component' => 'MintPanelSubpanels',
        ],
    ],
];

?>
