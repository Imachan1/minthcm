<?php

$dashletData['WorkSchedulesDashlet']['searchFields'] = array(
   'status' => array(
      'default' => '',
   ),
   'type' => array(
      'default' => '',
   ),
   'supervisor_acceptance' => array(
      'default' => '',
   ),
   'schedule_date' => array(
      'default' => '',
   ),
   'assigned_user_id' => array(
      'default' => '',
   ),
);
$dashletData['WorkSchedulesDashlet']['columns'] = array(
   'assigned_user_name' => array(
      'width' => '8%',
      'label' => 'LBL_LIST_ASSIGNED_USER',
      'name' => 'assigned_user_name',
      'default' => true,
   ),
   'schedule_date' => array(
      'type' => 'date',
      'label' => 'LBL_SCHEDULE_DATE',
      'width' => '10%',
      'default' => true,
   ),
   'type' => array(
      'type' => 'enum',
      'default' => true,
      'studio' => 'visible',
      'label' => 'LBL_TYPE',
      'width' => '10%',
   ),
   'status' => array(
      'type' => 'enum',
      'default' => true,
      'studio' => 'visible',
      'label' => 'LBL_STATUS',
      'width' => '10%',
   ),
   'supervisor_acceptance' => array(
      'type' => 'enum',
      'default' => false,
      'studio' => 'visible',
      'label' => 'LBL_SUPERVISOR_ACCEPTANCE',
      'width' => '10%',
   ),
   'spent_time' => array(
      'type' => 'float',
      'label' => 'LBL_SPENT_TIME',
      'width' => '10%',
      'default' => true,
   ),
   'name' => array(
      'width' => '40%',
      'label' => 'LBL_LIST_NAME',
      'link' => true,
      'default' => false,
      'name' => 'name',
   ),
   'delegation_duration' => array(
      'type' => 'float',
      'label' => 'LBL_DELEGATION_DURATION',
      'width' => '10%',
      'default' => false,
   ),
);
