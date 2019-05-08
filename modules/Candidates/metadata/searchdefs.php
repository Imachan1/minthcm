<?php

$module_name = 'Candidates';
$searchdefs [$module_name] = array(
   'layout' =>
   array(
      'basic_search' =>
      array(
         0 =>
         array(
            'name' => 'search_name',
            'label' => 'LBL_NAME',
            'type' => 'name',
         ),
         1 =>
         array(
            'name' => 'current_user_only',
            'label' => 'LBL_CURRENT_USER_FILTER',
            'type' => 'bool',
         ),
         2 =>
         array(
            'name' => 'favorites_only',
            'label' => 'LBL_FAVORITES_FILTER',
            'type' => 'bool',
         ),
      ),
      'advanced_search' =>
      array(
         'search_name' =>
         array(
            'label' => 'LBL_NAME',
            'type' => 'name',
            'width' => '10%',
            'default' => true,
            'name' => 'search_name',
         ),
         'first_name' =>
         array(
            'name' => 'first_name',
            'default' => true,
            'width' => '10%',
         ),
         'last_name' =>
         array(
            'name' => 'last_name',
            'default' => true,
            'width' => '10%',
         ),
         'potential' =>
         array(
            'name' => 'potential',
            'default' => true,
            'width' => '10%',
         ),
         'relocation' =>
         array(
            'name' => 'relocation',
            'default' => true,
            'width' => '10%',
         ),
         'collaboration' =>
         array(
            'name' => 'collaboration',
            'default' => true,
            'width' => '10%',
         ),
         'phone_mobile' =>
         array(
            'type' => 'phone',
            'label' => 'LBL_MOBILE_PHONE',
            'width' => '10%',
            'default' => true,
            'name' => 'phone_mobile',
         ),
         'primary_address_city' =>
         array(
            'type' => 'varchar',
            'label' => 'LBL_PRIMARY_ADDRESS_CITY',
            'width' => '10%',
            'default' => true,
            'name' => 'primary_address_city',
         ),
         'email' =>
         array(
            'name' => 'email',
            'label' => 'LBL_ANY_EMAIL',
            'type' => 'name',
            'default' => true,
            'width' => '10%',
         ),
         'primary_address_postalcode' =>
         array(
            'type' => 'varchar',
            'label' => 'LBL_PRIMARY_ADDRESS_POSTALCODE',
            'width' => '10%',
            'default' => true,
            'name' => 'primary_address_postalcode',
         ),
         'primary_address_state' =>
         array(
            'type' => 'varchar',
            'label' => 'LBL_PRIMARY_ADDRESS_STATE',
            'width' => '10%',
            'default' => true,
            'name' => 'primary_address_state',
         ),
         'primary_address_country' =>
         array(
            'type' => 'varchar',
            'default' => true,
            'label' => 'LBL_PRIMARY_ADDRESS_COUNTRY',
            'width' => '10%',
            'name' => 'primary_address_country',
         ),
         'primary_address_street' =>
         array(
            'type' => 'text',
            'label' => 'LBL_PRIMARY_STREET',
            'sortable' => false,
            'width' => '10%',
            'default' => true,
            'name' => 'primary_address_street',
         ),
         'assigned_user_id' =>
         array(
            'name' => 'assigned_user_id',
            'label' => 'LBL_ASSIGNED_TO',
            'type' => 'enum',
            'function' => array(
               'name' => 'get_user_array',
               'params' => array( false )
            )
         ),
      ),
   ),
   'templateMeta' =>
   array(
      'maxColumns' => '3',
      'maxColumnsBasic' => '4',
      'widths' =>
      array(
         'label' => '10',
         'field' => '30',
      ),
   ),
);
