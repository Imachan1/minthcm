<?php

$module_name = 'Contracts';
$listViewDefs [$module_name] = array(
   'NAME' =>
   array(
      'width' => '32%',
      'label' => 'LBL_NAME',
      'default' => true,
      'link' => true,
   ),
   'STATUS' =>
   array(
      'type' => 'enum',
      'default' => true,
      'studio' => 'visible',
      'label' => 'LBL_STATUS',
      'width' => '10%',
   ),
   'CONTRACT_TYPE' =>
   array(
      'type' => 'enum',
      'studio' => 'visible',
      'label' => 'LBL_CONTRACT_TYPE',
      'width' => '10%',
      'default' => true,
   ),
   'CONTRACT_STARTING_DATE' =>
   array(
      'type' => 'date',
      'label' => 'LBL_CONTRACT_STARTING_DATE',
      'width' => '10%',
      'default' => true,
   ),
   'CONTRACT_ENDING_DATE' =>
   array(
      'type' => 'date',
      'label' => 'LBL_CONTRACT_ENDING_DATE',
      'width' => '10%',
      'default' => true,
   ),
   'EMPLOYEE_NAME' =>
   array(
      'link' => true,
      'label' => 'LBL_EMPLOYEE_NAME',
      'id' => 'EMPLOYEE_ID',
      'width' => '10%',
      'default' => true,
   ),
   'PERIODOFEMPLOYMENT_NAME' =>
   array(
      'link' => true,
      'label' => 'LBL_PERIODOFEMPLOYMENT_NAME',
      'width' => '10%',
      'default' => true,
   ),
   'ASSIGNED_USER_NAME' =>
   array(
      'width' => '9%',
      'label' => 'LBL_ASSIGNED_TO_NAME',
      'module' => 'Employees',
      'id' => 'ASSIGNED_USER_ID',
      'default' => true,
   ),
   'DAILY_WORKING_TIME' =>
   array(
      'type' => 'enum',
      'studio' => 'visible',
      'label' => 'LBL_DAILY_WORKING_TIME',
      'width' => '10%',
      'default' => false,
   ),
   'DATE_OF_SIGNING' =>
   array(
      'type' => 'date',
      'label' => 'LBL_DATE_OF_SIGNING',
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
   'DATE_MODIFIED' =>
   array(
      'type' => 'datetime',
      'label' => 'LBL_DATE_MODIFIED',
      'width' => '10%',
      'default' => false,
   ),
   'DATE_ENTERED' =>
   array(
      'type' => 'datetime',
      'label' => 'LBL_DATE_ENTERED',
      'width' => '10%',
      'default' => false,
   ),
);
