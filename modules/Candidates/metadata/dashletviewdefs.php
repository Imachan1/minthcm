<?php

$dashletData['CandidatesDashlet']['searchFields'] = array(
   'name' =>
   array(
      'default' => '',
   ),
   'date_entered' =>
   array(
      'default' => '',
   ),
   'date_modified' =>
   array(
      'default' => '',
   ),
   'assigned_user_name' =>
   array(
      'default' => '',
   ),
   'last_name' =>
   array(
      'default' => '',
   ),
   'first_name' =>
   array(
      'default' => '',
   ),
   'phone_mobile' =>
   array(
      'default' => '',
   ),
   'primary_address_street' =>
   array(
      'default' => '',
   ),
   'relocation' =>
   array(
      'default' => '',
   ),
   'potential' =>
   array(
      'default' => '',
   ),
   'primary_address_country' =>
   array(
      'default' => '',
   ),
   'primary_address_postalcode' =>
   array(
      'default' => '',
   ),
   'primary_address_state' =>
   array(
      'default' => '',
   ),
   'primary_address_city' =>
   array(
      'default' => '',
   ),
   'email1' =>
   array(
      'default' => '',
   ),
);
$dashletData['CandidatesDashlet']['columns'] = array(
   'name' =>
   array(
      'width' => '40%',
      'label' => 'LBL_LIST_NAME',
      'link' => true,
      'default' => true,
      'name' => 'name',
   ),
   'date_entered' =>
   array(
      'width' => '15%',
      'label' => 'LBL_DATE_ENTERED',
      'default' => true,
      'name' => 'date_entered',
   ),
   'primary_address_city' =>
   array(
      'type' => 'varchar',
      'label' => 'LBL_PRIMARY_ADDRESS_CITY',
      'width' => '10%',
      'default' => true,
      'name' => 'primary_address_city',
   ),
   'date_modified' =>
   array(
      'width' => '15%',
      'label' => 'LBL_DATE_MODIFIED',
      'name' => 'date_modified',
      'default' => false,
   ),
   'created_by' =>
   array(
      'width' => '8%',
      'label' => 'LBL_CREATED',
      'name' => 'created_by',
      'default' => false,
   ),
   'assigned_user_name' =>
   array(
      'width' => '8%',
      'label' => 'LBL_LIST_ASSIGNED_USER',
      'name' => 'assigned_user_name',
      'default' => false,
   ),
   'phone_mobile' =>
   array(
      'type' => 'phone',
      'label' => 'LBL_MOBILE_PHONE',
      'width' => '10%',
      'default' => false,
      'name' => 'phone_mobile',
   ),
   'last_name' =>
   array(
      'type' => 'varchar',
      'label' => 'LBL_LAST_NAME',
      'width' => '10%',
      'default' => false,
      'name' => 'last_name',
   ),
   'email1' =>
   array(
      'type' => 'varchar',
      'studio' =>
      array(
         'editview' => true,
         'editField' => true,
         'searchview' => false,
         'popupsearch' => false,
      ),
      'label' => 'LBL_EMAIL_ADDRESS',
      'width' => '10%',
      'default' => false,
      'name' => 'email1',
   ),
   'primary_address_country' =>
   array(
      'type' => 'varchar',
      'default' => false,
      'label' => 'LBL_PRIMARY_ADDRESS_COUNTRY',
      'width' => '10%',
      'name' => 'primary_address_country',
   ),
   'primary_address_postalcode' =>
   array(
      'type' => 'varchar',
      'label' => 'LBL_PRIMARY_ADDRESS_POSTALCODE',
      'width' => '10%',
      'default' => false,
      'name' => 'primary_address_postalcode',
   ),
   'primary_address_state' =>
   array(
      'type' => 'varchar',
      'label' => 'LBL_PRIMARY_ADDRESS_STATE',
      'width' => '10%',
      'default' => false,
      'name' => 'primary_address_state',
   ),
   'primary_address_street' =>
   array(
      'type' => 'text',
      'label' => 'LBL_PRIMARY_ADDRESS_STREET',
      'sortable' => false,
      'width' => '10%',
      'default' => false,
      'name' => 'primary_address_street',
   ),
   'first_name' =>
   array(
      'type' => 'varchar',
      'label' => 'LBL_FIRST_NAME',
      'width' => '10%',
      'default' => false,
      'name' => 'first_name',
   ),
   'potential' =>
   array(
      'type' => 'enum',
      'default' => false,
      'studio' => 'visible',
      'label' => 'LBL_POTENTIAL',
      'width' => '10%',
      'name' => 'potential',
   ),
   'relocation' =>
   array(
      'label' => 'LBL_RELOCATION',
      'type' => 'bool',
      'width' => '10%',
      'default' => false,
   ),
   'birthdate' =>
   array(
      'label' => 'LBL_BIRTHDATE',
      'type' => 'date',
      'width' => '10%',
      'default' => false,
      'name' => 'birthdate',
   ),
);
