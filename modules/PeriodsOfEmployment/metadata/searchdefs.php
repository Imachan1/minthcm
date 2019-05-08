<?php

$module_name = 'PeriodsOfEmployment';
$searchdefs[$module_name] = array(
   'templateMeta' => array(
      'maxColumns' => '3',
      'maxColumnsBasic' => '4',
      'widths' => array( 'label' => '10', 'field' => '30' ),
   ),
   'layout' => array(
      'basic_search' =>
      array(
         'name' =>
         array(
            'name' => 'name',
            'default' => true,
            'width' => '10%',
         ),
         'current_user_only' =>
         array(
            'name' => 'current_user_only',
            'label' => 'LBL_CURRENT_USER_FILTER',
            'type' => 'bool',
            'default' => true,
            'width' => '10%',
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
         'period_starting_date' =>
         array(
            'type' => 'date',
            'default' => true,
            'label' => 'LBL_PERIOD_STARTING_DATE',
            'width' => '10%',
            'name' => 'period_starting_date',
         ),
         'period_ending_date' =>
         array(
            'type' => 'date',
            'default' => true,
            'label' => 'LBL_PERIOD_ENDING_DATE',
            'width' => '10%',
            'name' => 'period_ending_date',
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
            'default' => true,
            'width' => '10%',
         ),
         'employee_id' =>
         array(
            'name' => 'employee_id',
            'label' => 'LBL_EMPLOYEE',
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
);
