<?php

$dashletData['ApplicationsDashlet']['searchFields'] = array(
   'status' =>
   array(
      'default' => '',
   ),
   'type' =>
   array(
      'default' => '',
   ),
   'employee_name' => array(
      'default' => '',
      'name' => 'employee_name',
      'label' => 'LBL_EMPLOYEE'
   ),
   'assigned_user_name' => array( 'default' => '' ),
);
$dashletData['ApplicationsDashlet']['columns'] = array(
   'name' =>
   array(
      'width' => '40%',
      'label' => 'LBL_NAME',
      'link' => true,
      'default' => true,
      'name' => 'name',
   ),
   'status' =>
   array(
      'type' => 'enum',
      'width' => '15%',
      'label' => 'LBL_STATUS',
      'name' => 'status',
      'default' => false,
   ),
   'type' =>
   array(
      'type' => 'enum',
      'width' => '15%',
      'label' => 'LBL_TYPE',
      'name' => 'type',
      'default' => false,
   ),
   'employee_name' => array(
      'width' => '15',
      'label' => 'LBL_EMPLOYEE',
      'default' => false
   ),
   'date_modified' =>
   array(
      'width' => '15%',
      'label' => 'LBL_DATE_MODIFIED',
      'name' => 'date_modified',
      'default' => true,
   ),
   'date_entered' =>
   array(
      'width' => '15%',
      'label' => 'LBL_DATE_ENTERED',
      'default' => true,
      'name' => 'date_entered',
   ),
   'assigned_user_name' =>
   array(
      'width' => '8%',
      'label' => 'LBL_ASSIGNED_TO_NAME',
      'name' => 'assigned_user_name',
      'default' => true,
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
