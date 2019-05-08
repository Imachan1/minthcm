<?php

$module_name = 'Offboardings';
$listViewDefs [$module_name] = array(
   'NAME' =>
   array(
      'width' => '32%',
      'label' => 'LBL_NAME',
      'default' => true,
      'link' => true,
   ),
   'DATE_START' =>
   array(
      'type' => 'datetimecombo',
      'label' => 'LBL_DATE_START',
      'width' => '10%',
      'default' => true,
   ),
   'STATUS' =>
   array(
      'type' => 'enum',
      'default' => true,
      'studio' => 'visible',
      'label' => 'LBL_STATUS',
      'width' => '10%',
   ),
   'OFFBOARDINGTEMPLATE_NAME' =>
   array(
      'type' => 'relate',
      'link' => true,
      'label' => 'LBL_OFFBOARDINGTEMPLATE_NAME',
      'id' => 'OFFBOARDINGTEMPLATE_ID',
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
   'ASSIGNED_USER_NAME' =>
   array(
      'width' => '9%',
      'label' => 'LBL_ASSIGNED_TO_NAME',
      'module' => 'Employees',
      'id' => 'ASSIGNED_USER_ID',
      'default' => true,
   ),
);
