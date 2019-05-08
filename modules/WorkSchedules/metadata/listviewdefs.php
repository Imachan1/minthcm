<?php

$module_name = 'WorkSchedules';
$listViewDefs[$module_name] = array(
   'NAME' => array(
      'width' => '32%',
      'label' => 'LBL_NAME',
      'default' => true,
      'link' => true,
   ),
   'SCHEDULE_DATE' => array(
      'type' => 'date',
      'label' => 'LBL_SCHEDULE_DATE',
      'width' => '10%',
      'default' => true,
   ),
   'TYPE' => array(
      'type' => 'enum',
      'default' => true,
      'studio' => 'visible',
      'label' => 'LBL_TYPE',
      'width' => '10%',
   ),
   'STATUS' => array(
      'type' => 'enum',
      'default' => true,
      'studio' => 'visible',
      'label' => 'LBL_STATUS',
      'width' => '10%',
   ),
   'SUPERVISOR_ACCEPTANCE' => array(
      'type' => 'enum',
      'default' => true,
      'studio' => 'visible',
      'label' => 'LBL_SUPERVISOR_ACCEPTANCE',
      'width' => '10%',
   ),
   'ASSIGNED_USER_NAME' => array(
      'width' => '9%',
      'label' => 'LBL_ASSIGNED_TO_NAME',
      'module' => 'Employees',
      'id' => 'ASSIGNED_USER_ID',
      'default' => true,
   ),
   'DATE_START' => array(
      'type' => 'datetimecombo',
      'label' => 'LBL_DATE_START',
      'width' => '10%',
      'default' => false,
   ),
   'SPENT_TIME' => array(
      'type' => 'float',
      'label' => 'LBL_SPENT_TIME',
      'width' => '10%',
      'default' => false,
   ),
   'DATE_END' => array(
      'type' => 'datetimecombo',
      'label' => 'LBL_DATE_END',
      'width' => '10%',
      'default' => false,
   ),
   'DELEGATION_DURATION' => array(
      'type' => 'float',
      'label' => 'LBL_DELEGATION_DURATION',
      'width' => '10%',
      'default' => false,
   ),
);
?>
