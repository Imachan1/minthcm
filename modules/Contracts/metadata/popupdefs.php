<?php

$popupMeta = array(
   'moduleMain' => 'Contracts',
   'varName' => 'Contracts',
   'orderBy' => 'contracts.name',
   'whereClauses' => array(
      'name' => 'contracts.name',
      'status' => 'contracts.status',
      'contract_type' => 'contracts.contract_type',
      'daily_working_time' => 'contracts.daily_working_time',
      'date_of_signing' => 'contracts.date_of_signing',
      'contract_starting_date' => 'contracts.contract_starting_date',
      'contract_ending_date' => 'contracts.contract_ending_date',
      'assigned_user_id' => 'contracts.assigned_user_id',
   ),
   'searchInputs' => array(
      'name',
      'status',
      'contract_type',
      'daily_working_time',
      'date_of_signing',
      'contract_starting_date',
      'contract_ending_date',
      'assigned_user_id',
   ),
   'searchdefs' => array(
      'name' =>
      array(
         'name' => 'name',
         'width' => '10%',
      ),
      'status' =>
      array(
         'type' => 'enum',
         'studio' => 'visible',
         'label' => 'LBL_STATUS',
         'width' => '10%',
         'name' => 'status',
      ),
      'periodofemployment_name' =>
      array(
         'type' => 'enum',
         'label' => 'LBL_PERIODOFEMPLOYMENT_NAME',
         'width' => '10%',
         'name' => 'periodofemployment_name',
      ),
      'contract_type' =>
      array(
         'type' => 'enum',
         'studio' => 'visible',
         'label' => 'LBL_CONTRACT_TYPE',
         'width' => '10%',
         'name' => 'contract_type',
      ),
      'daily_working_time' =>
      array(
         'type' => 'enum',
         'studio' => 'visible',
         'label' => 'LBL_DAILY_WORKING_TIME',
         'width' => '10%',
         'name' => 'daily_working_time',
      ),
      'date_of_signing' =>
      array(
         'type' => 'date',
         'label' => 'LBL_DATE_OF_SIGNING',
         'width' => '10%',
         'name' => 'date_of_signing',
      ),
      'contract_starting_date' =>
      array(
         'type' => 'date',
         'label' => 'LBL_CONTRACT_STARTING_DATE',
         'width' => '10%',
         'name' => 'contract_starting_date',
      ),
      'contract_ending_date' =>
      array(
         'type' => 'date',
         'label' => 'LBL_CONTRACT_ENDING_DATE',
         'width' => '10%',
         'name' => 'contract_ending_date',
      ),
      'employee_name' =>
      array(
         'name' => 'employee_id',
         'label' => 'LBL_EMPLOYEE_NAME',
         'type' => 'enum',
         'function' =>
         array(
            'name' => 'get_user_array',
            'params' =>
            array(
               false,
            ),
         ),
         'default' => true,
         'width' => '10%',
      ),
      'assigned_user_id' =>
      array(
         'name' => 'assigned_user_id',
         'label' => 'LBL_ASSIGNED_TO',
         'type' => 'enum',
         'function' =>
         array(
            'name' => 'get_user_array',
            'params' =>
            array(
               0 => false,
            ),
         ),
         'width' => '10%',
      ),
   ),
);
