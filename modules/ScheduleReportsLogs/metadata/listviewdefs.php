<?php

$module_name = 'ScheduleReportsLogs';
$listViewDefs [$module_name] = array(
   'NAME' =>
   array(
      'width' => '32%',
      'label' => 'LBL_NAME',
      'default' => true,
      'link' => true,
   ),
   'EXECUTE_DATA' =>
   array(
      'type' => 'datetimecombo',
      'label' => 'LBL_EXECUTE_DATA',
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
   'SCHEDULE_REPORT_NAME' =>
   array(
      'type' => 'relate',
      'link' => true,
      'label' => 'LBL_SCHEDULE_REPORT',
      'id' => 'SCHEDULE_REPORT_ID',
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
?>
