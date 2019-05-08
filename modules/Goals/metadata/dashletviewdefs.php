<?php

$dashletData['GoalsDashlet']['searchFields'] = array(
   'name' =>
   array(
      'default' => '',
   ),
   'date_start' =>
   array(
      'default' => '',
   ),
   'date_end' =>
   array(
      'default' => '',
   ),
   'status' =>
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
$dashletData['GoalsDashlet']['columns'] = array(
   'name' =>
   array(
      'width' => '40%',
      'label' => 'LBL_LIST_NAME',
      'link' => true,
      'default' => true,
      'name' => 'name',
   ),
   'status' =>
   array(
      'type' => 'enum',
      'default' => true,
      'studio' => 'visible',
      'label' => 'LBL_STATUS',
      'width' => '10%',
      'name' => 'status',
   ),
   'date_start' =>
   array(
      'type' => 'datetimecombo',
      'label' => 'LBL_DATE_START',
      'width' => '10%',
      'default' => true,
      'name' => 'date_start',
   ),
   'date_end' =>
   array(
      'type' => 'datetimecombo',
      'label' => 'LBL_DATE_END',
      'width' => '10%',
      'default' => true,
      'name' => 'date_end',
   ),
   'employee_name' =>
   array(
      'width' => '8%',
      'label' => 'LBL_EMPLOYEE_NAME',
      'name' => 'employee_name',
      'default' => false,
   ),
   'date_modified' =>
   array(
      'width' => '15%',
      'label' => 'LBL_DATE_MODIFIED',
      'name' => 'date_modified',
      'default' => false,
   ),
   'date_entered' =>
   array(
      'width' => '15%',
      'label' => 'LBL_DATE_ENTERED',
      'default' => false,
      'name' => 'date_entered',
   ),
   'assigned_user_name' =>
   array(
      'width' => '8%',
      'label' => 'LBL_LIST_ASSIGNED_USER',
      'name' => 'assigned_user_name',
      'default' => false,
   ),
   'created_by_name' =>
   array(
      'type' => 'relate',
      'link' => true,
      'label' => 'LBL_CREATED',
      'id' => 'CREATED_BY',
      'width' => '10%',
      'default' => false,
   ),
   'modified_by_name' =>
   array(
      'type' => 'relate',
      'link' => true,
      'label' => 'LBL_MODIFIED_NAME',
      'id' => 'MODIFIED_USER_ID',
      'width' => '10%',
      'default' => false,
   ),
);
