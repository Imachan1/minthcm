<?php

$module_name = 'Contracts';
$searchdefs [$module_name] = array(
   'layout' =>
   array(
      'basic_search' =>
      array(
         'name',
         array(
            'name' => 'current_user_only',
            'label' => 'LBL_CURRENT_USER_FILTER',
            'type' => 'bool',
         ),
      ),
      'advanced_search' =>
      array(
         'name' =>
         array(
            'name' => 'name',
            'default' => true,
            'width' => '10%',
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
         'contract_type' =>
         array(
            'type' => 'enum',
            'studio' => 'visible',
            'label' => 'LBL_CONTRACT_TYPE',
            'width' => '10%',
            'default' => true,
            'name' => 'contract_type',
         ),
         'periodofemployment_name' =>
         array(
            'type' => 'relate',
            'link' => true,
            'studio' => 'visible',
            'label' => 'LBL_PERIODOFEMPLOYMENT_NAME',
            'width' => '10%',
            'default' => true,
            'name' => 'periodofemployment_name',
         ),
         'date_of_signing' =>
         array(
            'type' => 'date',
            'label' => 'LBL_DATE_OF_SIGNING',
            'width' => '10%',
            'default' => true,
            'name' => 'date_of_signing',
         ),
         'contract_starting_date' =>
         array(
            'type' => 'date',
            'label' => 'LBL_CONTRACT_STARTING_DATE',
            'width' => '10%',
            'default' => true,
            'name' => 'contract_starting_date',
         ),
         'contract_ending_date' =>
         array(
            'type' => 'date',
            'label' => 'LBL_CONTRACT_ENDING_DATE',
            'width' => '10%',
            'default' => true,
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
                  false,
               ),
            ),
            'default' => true,
            'width' => '10%',
         ),
      ),
   ),
   'templateMeta' =>
   array(
      'maxColumns' => '3',
      'maxColumnsBasic' => '4',
      'widths' =>
      array(
         'label' => '10',
         'field' => '30',
      ),
   ),
);
