<?php

$dashletData['AppraisalsDashlet']['searchFields'] = array(
   'name' =>
   array(
      'default' => '',
   ),
   'date' =>
   array(
      'default' => '',
   ),
   'type' =>
   array(
      'default' => '',
   ),
   'candidature_name' =>
   array(
      'default' => '',
   ),
   'employee_name' =>
   array(
      'default' => '',
   ),
   'position_name' =>
   array(
      'default' => '',
   ),
   'assigned_user_name' =>
   array(
      'default' => '',
   ),
   'status' =>
   array(
      'default' => '',
   ),
);
$dashletData['AppraisalsDashlet']['columns'] = array(
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
   'type' =>
   array(
      'type' => 'enum',
      'default' => true,
      'studio' => 'visible',
      'label' => 'LBL_TYPE',
      'width' => '10%',
      'name' => 'type',
   ),
   'date' =>
   array(
      'type' => 'datet',
      'label' => 'LBL_DATE',
      'width' => '10%',
      'default' => true,
      'name' => 'date',
   ),
   'employee_name' =>
   array(
      'width' => '8%',
      'label' => 'LBL_EMPLOYEE_NAME',
      'name' => 'employee_name',
      'default' => true,
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
   'position_name' =>
   array(
      'name' => 'position_name',
      'label' => 'LBL_POSITION_NAME',
      'type' => 'relate',
      'default' => false,
      'width' => '10%',
   ),
   'candidature_name' =>
   array(
      'name' => 'candidature_name',
      'label' => 'LBL_CANDIDATURE_NAME',
      'type' => 'relate',
      'default' => false,
      'width' => '10%',
   ),
);
