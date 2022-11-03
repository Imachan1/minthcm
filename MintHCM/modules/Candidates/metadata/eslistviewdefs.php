<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$module_name = 'Candidates';
$ESListViewDefs[$module_name] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'phone_mobile' => [
            'default' => true,
        ],
        'recr_contact_agree' => [
        ],
        'potential' => [
            'default' => true,
        ],
        'birthdate' => [
            'default' => true,
        ],
        'relocation' => [],
        'last_time_contact' => [],
        'date_planned_contact' => [],
        'date_entered' => [],
        'assigned_user_name' => [
            'link' => true,
            'default' => true,
        ],
        'skype' => [],
        'goldenline' => [],
        'last_name' => [],
        'linkedin' => [],
        'first_name' => [],
        'created_by_name' => [],
        'primary_address_street' => [],
        'facebook' => [],
        'primary_address_city' => [
            'default' => true,
        ],
        'primary_address_state' => [],
        'primary_address_country' => [],
        'primary_address_postalcode' => [],
    ],
    'search' => [
        'last_time_contact' => [],
        'date_planned_contact' => [],
        'phone_mobile' => [],
        'potential' => [],
        'birthdate' => [],
        'relocation' => [],
        'date_entered' => [],
    ]
];
