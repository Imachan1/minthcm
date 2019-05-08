<?php

$dashletData['WorkingMonthsDashlet']['searchFields'] = array(
   'year' => array(
      'default' => '',
   ),
   'months' => array(
      'default' => '',
   ),
   'working_hours' => array(
      'default' => '',
   ),
   'working_days' => array(
      'default' => '',
   ),
);
$dashletData['WorkingMonthsDashlet']['columns'] = array(
   'name' =>
   array(
      'width' => '40%',
      'label' => 'LBL_LIST_NAME',
      'link' => true,
      'default' => true,
      'name' => 'name',
   ),
   'working_hours' =>
   array(
      'type' => 'int',
      'label' => 'LBL_WORKING_HOURS',
      'width' => '10%',
      'default' => true,
   ),
   'working_days' =>
   array(
      'type' => 'int',
      'label' => 'LBL_WORKING_DAYS',
      'width' => '10%',
      'default' => true,
   ),
   'year' =>
   array(
      'type' => 'int',
      'label' => 'LBL_YEAR',
      'width' => '10%',
      'default' => false,
   ),
   'months' =>
   array(
      'type' => 'enum',
      'default' => false,
      'studio' => 'visible',
      'label' => 'LBL_MONTHS',
      'width' => '10%',
   ),
   'date_entered' =>
   array(
      'width' => '15%',
      'label' => 'LBL_DATE_ENTERED',
      'default' => false,
      'name' => 'date_entered',
   ),
   'date_modified' =>
   array(
      'width' => '15%',
      'label' => 'LBL_DATE_MODIFIED',
      'name' => 'date_modified',
      'default' => false,
   ),
   'created_by' =>
   array(
      'width' => '8%',
      'label' => 'LBL_CREATED',
      'name' => 'created_by',
      'default' => false,
   ),
   'assigned_user_name' =>
   array(
      'width' => '8%',
      'label' => 'LBL_LIST_ASSIGNED_USER',
      'name' => 'assigned_user_name',
      'default' => false,
   ),
);
