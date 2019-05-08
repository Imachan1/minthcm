<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

global $current_user;

$dashletData['PeriodsOfEmploymentDashlet']['searchFields'] = array(
   'date_entered' => array( 'default' => '' ),
   'date_modified' => array( 'default' => '' ),
   'employee_name' => array( 'default' => '' ),

   //studio extension_loaded
   'name' =>
   array(
      'default' => '',
   ),
   'period_starting_date' =>
   array(
      'default' => '',
   ),
   'period_ending_date' =>
   array(
      'default' => '',
   ),
   'modified_by_name' =>
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
   'assigned_user_id' =>
   array(
      'default' => '',
   ),
);
$dashletData['PeriodsOfEmploymentDashlet']['columns'] = array(
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
   'period_starting_date' =>
   array(
      'type' => 'date',
      'default' => true,
      'label' => 'LBL_PERIOD_STARTING_DATE',
      'width' => '10%',
   ),
   'period_ending_date' =>
   array(
      'type' => 'date',
      'default' => true,
      'label' => 'LBL_PERIOD_ENDING_DATE',
      'width' => '10%',
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
   'modified_by_name' =>
   array(
      'type' => 'relate',
      'link' => true,
      'label' => 'LBL_MODIFIED_NAME',
      'id' => 'MODIFIED_USER_ID',
      'width' => '10%',
      'default' => false,
   ),
   'created_by_name' =>
   array(
      'type' => 'relate',
      'link' => true,
      'label' => 'LBL_CREATED',
      'id' => 'CREATED_BY',
      'width' => '10%',
      'default' => false,
   ),
   'employee_name' => array(
      'width' => '15',
      'label' => 'LBL_EMPLOYEE',
      'default' => false
   ),
);
