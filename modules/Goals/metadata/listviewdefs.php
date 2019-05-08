<?php

$module_name = 'Goals';
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
   'DATE_START' =>
   array(
      'type' => 'datetimecombo',
      'label' => 'LBL_DATE_START',
      'width' => '10%',
      'default' => true,
   ),
   'DATE_END' =>
   array(
      'type' => 'datetimecombo',
      'label' => 'LBL_DATE_END',
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
   'ASSIGNED_USER_NAME' =>
   array(
      'width' => '9%',
      'label' => 'LBL_ASSIGNED_TO_NAME',
      'module' => 'Employees',
      'id' => 'ASSIGNED_USER_ID',
      'default' => true,
   ),
);
;
?>
