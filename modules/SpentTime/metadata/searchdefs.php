<?php

$module_name = 'SpentTime';
$searchdefs[$module_name] = array(
   'templateMeta' => array(
      'maxColumns' => '3',
      'maxColumnsBasic' => '4',
      'widths' => array( 'label' => '10', 'field' => '30' ),
   ),
   'layout' => array(
      'basic_search' => array(
         'name',
         array
            (
            'name' => 'current_user_only',
            'label' => 'LBL_CURRENT_USER_FILTER',
            'type' => 'bool'
         ),
      ),
      'advanced_search' => array(
         'name',
         array
            (
            'name' => 'spent_time',
            'label' => 'LBL_SPENT_TIME',
            'type' => 'float',
         ),
         array
            (
            'name' => 'work_date',
            'label' => 'LBL_WORK_DATE',
            'type' => 'date',
         ),
         array
            (
            'name' => 'date_start',
            'label' => 'LBL_DATE_START',
            'type' => 'date',
         ),
         array
            (
            'name' => 'date_end',
            'label' => 'LBL_DATE_END',
            'type' => 'date',
         ),
         array(
            'name' => 'type',
            'label' => 'LBL_SPENT_TIME_TYPE',
            'type' => 'enum',
         ),
         array
            (
            'name' => 'assigned_user_id',
            'label' => 'LBL_ASSIGNED_TO',
            'type' => 'enum',
            'function' => array
               (
               'name' => 'get_user_array',
               'params' => array( false )
            )
         ),
      ),
   ),
);
