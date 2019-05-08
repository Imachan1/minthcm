<?php

$module_name = 'OrganizationalUnits';
$listViewDefs [$module_name] = array(
   'NAME' =>
   array(
      'width' => '32%',
      'label' => 'LBL_NAME',
      'default' => true,
      'link' => true,
   ),
   'TYPE' =>
   array(
      'type' => 'enum',
      'studio' => 'visible',
      'label' => 'LBL_TYPE',
      'width' => '10%',
      'default' => true,
   ),
   'PARENT_NAME' =>
   array(
      'type' => 'relate',
      'link' => true,
      'label' => 'LBL_MEMBER_OF',
      'id' => 'PARENT_ID',
      'width' => '10%',
      'default' => true,
   ),
   'POSITION_LEADER_NAME' =>
   array(
      'type' => 'relate',
      'link' => true,
      'label' => 'LBL_POSITION_LEADER_NAME',
      'id' => 'POSITION_LEADER_ID',
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
   'DATE_MODIFIED' =>
   array(
      'type' => 'datetime',
      'label' => 'LBL_DATE_MODIFIED',
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
   'EMPLOYEE_NAME' => array(
      'type' => 'relate',
      'link' => true,
      'label' => 'LBL_CURRENT_MANAGER_NAME',
      'id' => 'CURRENT_MANAGER_ID',
      'width' => '10%',
      'default' => true,
   ),
);
;
?>
