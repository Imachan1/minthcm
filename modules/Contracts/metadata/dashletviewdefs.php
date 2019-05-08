<?php

$dashletData['ContractsDashlet']['searchFields'] = array(
   'name' =>
   array(
      'default' => '',
   ),
   'contract_starting_date' =>
   array(
      'default' => '',
   ),
   'contract_ending_date' =>
   array(
      'default' => '',
   ),
   'status' =>
   array(
      'default' => '',
   ),
   'contract_type' =>
   array(
      'default' => '',
   ),
   'daily_working_time' =>
   array(
      'default' => '',
   ),
   'date_of_signing' =>
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
   'employee_name' =>
   array(
      'default' => '',
   ),
   'assigned_user_name' =>
   array(
      'default' => '',
   ),
   'periodofemployment_name' =>
   array(
      'default' => '',
   ),
);
$dashletData['ContractsDashlet']['columns'] = array(
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
   ),
   'contract_type' =>
   array(
      'type' => 'enum',
      'studio' => 'visible',
      'label' => 'LBL_CONTRACT_TYPE',
      'width' => '10%',
      'default' => true,
   ),
   'contract_starting_date' =>
   array(
      'type' => 'date',
      'label' => 'LBL_CONTRACT_STARTING_DATE',
      'width' => '10%',
      'default' => true,
   ),
   'contract_ending_date' =>
   array(
      'type' => 'date',
      'label' => 'LBL_CONTRACT_ENDING_DATE',
      'width' => '10%',
      'default' => true,
   ),
   'date_modified' =>
   array(
      'width' => '15%',
      'label' => 'LBL_DATE_MODIFIED',
      'name' => 'date_modified',
      'default' => false,
   ),
   'date_of_signing' =>
   array(
      'type' => 'date',
      'label' => 'LBL_DATE_OF_SIGNING',
      'width' => '10%',
      'default' => false,
   ),
   'created_by' =>
   array(
      'width' => '8%',
      'label' => 'LBL_CREATED',
      'name' => 'created_by',
      'default' => false,
   ),
   'employee_name' =>
   array(
      'width' => '8%',
      'label' => 'LBL_EMPLOYEE_NAME',
      'name' => 'employee_name',
      'default' => false,
   ),
   'assigned_user_name' =>
   array(
      'width' => '8%',
      'label' => 'LBL_LIST_ASSIGNED_USER',
      'name' => 'assigned_user_name',
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
   'daily_working_time' =>
   array(
      'type' => 'enum',
      'studio' => 'visible',
      'label' => 'LBL_DAILY_WORKING_TIME',
      'width' => '10%',
      'default' => false,
   ),
   'date_entered' =>
   array(
      'width' => '15%',
      'label' => 'LBL_DATE_ENTERED',
      'default' => false,
      'name' => 'date_entered',
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
   'periodofemployment_name' =>
   array(
      'type' => 'relate',
      'link' => true,
      'label' => 'LBL_PERIODOFEMPLOYMENT_NAME',
      'width' => '10%',
      'default' => false,
   ),
);
