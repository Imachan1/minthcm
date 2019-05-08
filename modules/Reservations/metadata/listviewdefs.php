<?php

$module_name = 'Reservations';
$listViewDefs [$module_name] = array(
   'NAME' =>
   array(
      'width' => '32%',
      'label' => 'LBL_NAME',
      'default' => true,
      'link' => true,
   ),
   'ASSIGNED_USER_NAME' =>
   array(
      'width' => '9%',
      'label' => 'LBL_ASSIGNED_TO_NAME',
      'module' => 'Employees',
      'id' => 'ASSIGNED_USER_ID',
      'default' => true,
   ),
   'STARTING_DATE' =>
   array(
      'type' => 'datetimecombo',
      'label' => 'LBL_STARTING_DATE',
      'width' => '10%',
      'default' => true,
   ),
   'ENDING_DATE' =>
   array(
      'type' => 'datetimecombo',
      'label' => 'LBL_ENDING_DATE',
      'width' => '10%',
      'default' => true,
   ),
   'resource_name' =>
   array(
      'type' => 'relate',
      'link' => true,
      'label' => 'LBL_RESOURCES',
      'id' => 'RESOURCE_ID',
      'width' => '10%',
      'default' => true,
   ),
   'PARENT_NAME' => array(
      'width' => '25',
      'label' => 'LBL_PARENT_NAME',
      'sortable' => false,
      'dynamic_module' => 'PARENT_TYPE',
      'id' => 'PARENT_ID',
      'link' => true,
      'ACLTag' => 'PARENT',
      'related_fields' => array( 'parent_id', 'parent_type' ),
      'default' => true,
   ),
   'DELEGATION_NAME' =>
   array(
      'type' => 'relate',
      'link' => true,
      'label' => 'LBL_DELEGATIONS',
      'id' => 'DELEGATION_ID',
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
?>
