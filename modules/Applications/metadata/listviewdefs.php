<?php

$module_name = 'Applications';
$listViewDefs [$module_name] = array(
   'NAME' =>
   array(
      'label' => 'LBL_NAME',
      'link' => true,
      'orderBy' => 'name',
      'default' => true,
      'width' => '10%',
   ),
   'STATUS' =>
   array(
      'type' => 'enum',
      'default' => true,
      'studio' => 'visible',
      'label' => 'LBL_STATUS',
      'width' => '10%',
   ),
   'TYPE' =>
   array(
      'type' => 'enum',
      'default' => true,
      'studio' => 'visible',
      'label' => 'LBL_TYPE',
      'width' => '10%',
   ),
   'ASSIGNED_USER_NAME' =>
   array(
      'width' => '9%',
      'label' => 'LBL_ASSIGNED_TO_NAME',
      'module' => 'Employees',
      'id' => 'ASSIGNED_USER_ID',
      'default' => true,
   ),
   'employee_name' =>
   array(
      'label' => 'LBL_EMPLOYEE',
      'width' => '10%',
      'default' => true,
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
      'default' => true,
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
);
