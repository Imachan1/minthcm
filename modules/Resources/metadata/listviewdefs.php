<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

$module_name = 'Resources';
$listViewDefs[$module_name] = array(
   'NAME' =>
   array(
      'width' => '32%',
      'label' => 'LBL_NAME',
      'default' => true,
      'link' => true,
   ),
   'UNAVAILABLE' =>
   array(
      'type' => 'bool',
      'default' => true,
      'label' => 'LBL_UNAVAILABLE',
      'width' => '10%',
   ),
   'TYPE' =>
   array(
      'type' => 'enum',
      'studio' => 'visible',
      'label' => 'LBL_TYPE',
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
   'DATE_ENTERED' =>
   array(
      'type' => 'datetime',
      'label' => 'LBL_DATE_ENTERED',
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
   'employee_name' =>
   array(
      'label' => 'LBL_EMPLOYEE',
      'width' => '10%',
      'default' => true,
   ),
);
