<?php

$dashletData['OffboardingsDashlet']['searchFields'] = array(
   'name' =>
   array(
      'default' => '',
   ),
   'date_start' =>
   array(
      'default' => '',
   ),
   'status' =>
   array(
      'default' => '',
   ),
   'offboardingtemplate_name' =>
   array(
      'default' => '',
   ),
   'employee_name' =>
   array(
      'default' => '',
   ),
   'date_entered' =>
   array(
      'default' => '',
   ),
   'date_modified' =>
   array(
      'default' => '',
   ),
   'assigned_user_name' =>
   array(
      'default' => '',
   ),
);
$dashletData['OffboardingsDashlet']['columns'] = array(
   'name' =>
   array(
      'width' => '40%',
      'label' => 'LBL_LIST_NAME',
      'link' => true,
      'default' => true,
      'name' => 'name',
   ),
   'date_start' =>
   array(
      'type' => 'datetimecombo',
      'label' => 'LBL_DATE_START',
      'width' => '10%',
      'default' => true,
   ),
   'status' =>
   array(
      'type' => 'enum',
      'default' => true,
      'studio' => 'visible',
      'label' => 'LBL_STATUS',
      'width' => '10%',
   ),
   'offboardingtemplate_name' =>
   array(
      'width' => '8%',
      'label' => 'LBL_OFFBOARDINGTEMPLATE_NAME',
      'name' => 'offboardingtemplate_name',
      'default' => true,
   ),
   'employee_name' =>
   array(
      'width' => '8%',
      'label' => 'LBL_EMPLOYEE_NAME',
      'name' => 'employee_name',
      'default' => true,
   ),
   'assigned_user_name' =>
   array(
      'width' => '8%',
      'label' => 'LBL_LIST_ASSIGNED_USER',
      'name' => 'assigned_user_name',
      'default' => true,
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
);
