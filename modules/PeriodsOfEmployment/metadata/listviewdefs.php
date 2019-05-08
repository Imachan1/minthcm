<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

$module_name = 'PeriodsOfEmployment';
$listViewDefs[$module_name] = array(
   'NAME' =>
   array(
      'width' => '32%',
      'label' => 'LBL_NAME',
      'default' => true,
      'link' => true,
   ),
   'PERIOD_STARTING_DATE' =>
   array(
      'type' => 'date',
      'default' => true,
      'label' => 'LBL_PERIOD_STARTING_DATE',
      'width' => '10%',
   ),
   'PERIOD_ENDING_DATE' =>
   array(
      'type' => 'date',
      'default' => true,
      'label' => 'LBL_PERIOD_ENDING_DATE',
      'width' => '10%',
   ),
   'DATE_MODIFIED' =>
   array(
      'type' => 'datetime',
      'label' => 'LBL_DATE_MODIFIED',
      'width' => '10%',
      'default' => true,
   ),
   'DATE_ENTERED' =>
   array(
      'type' => 'datetime',
      'label' => 'LBL_DATE_ENTERED',
      'width' => '10%',
      'default' => false,
   ),
   'CREATED_BY_NAME' =>
   array(
      'type' => 'relate',
      'link' => true,
      'label' => 'LBL_CREATED',
      'id' => 'CREATED_BY',
      'width' => '10%',
      'default' => false,
   ),
   'MODIFIED_BY_NAME' =>
   array(
      'type' => 'relate',
      'link' => true,
      'label' => 'LBL_MODIFIED_NAME',
      'id' => 'MODIFIED_USER_ID',
      'width' => '10%',
      'default' => false,
   ),
   'employee_name' =>
   array(
      'label' => 'LBL_EMPLOYEE',
      'width' => '10%',
      'default' => true,
   ),
);
