<?php

$module_name = 'WorkSchedules';
$searchdefs[$module_name] = array(
   'layout' => array(
      'basic_search' => array(
         'name' => array(
            'name' => 'name',
            'default' => true,
            'width' => '10%',
         ),
         'current_user_only' => array(
            'name' => 'current_user_only',
            'label' => 'LBL_CURRENT_USER_FILTER',
            'type' => 'bool',
            'default' => true,
            'width' => '10%',
         ),
      ),
      'advanced_search' => array(
         'assigned_user_id' => array(
            'name' => 'assigned_user_id',
            'label' => 'LBL_ASSIGNED_TO',
            'type' => 'enum',
            'function' => array(
               'name' => 'get_user_array',
               'params' => array(
                  0 => false,
               ),
            ),
            'default' => true,
            'width' => '10%',
         ),
         'type' => array(
            'type' => 'enum',
            'default' => true,
            'studio' => 'visible',
            'label' => 'LBL_TYPE',
            'width' => '10%',
            'name' => 'type',
         ),
         'status' => array(
            'type' => 'enum',
            'default' => true,
            'studio' => 'visible',
            'label' => 'LBL_STATUS',
            'width' => '10%',
            'name' => 'status',
         ),
         'supervisor_acceptance' => array(
            'type' => 'enum',
            'default' => true,
            'studio' => 'visible',
            'label' => 'LBL_SUPERVISOR_ACCEPTANCE',
            'width' => '10%',
            'name' => 'supervisor_acceptance',
         ),
         'schedule_date' => array(
            'type' => 'date',
            'label' => 'LBL_SCHEDULE_DATE',
            'width' => '10%',
            'default' => true,
            'name' => 'schedule_date',
         ),
         'date_start' => array(
            'type' => 'date',
            'label' => 'LBL_DATE_START',
            'width' => '10%',
            'default' => true,
            'name' => 'date_start',
         ),
         'date_end' => array(
            'type' => 'date',
            'label' => 'LBL_DATE_END',
            'width' => '10%',
            'default' => true,
            'name' => 'date_end',
         ),
         'spent_time' => array(
            'type' => 'float',
            'label' => 'LBL_SPENT_TIME',
            'width' => '10%',
            'default' => true,
            'name' => 'spent_time',
         ),
         'delegation_duration' => array(
            'type' => 'float',
            'label' => 'LBL_DELEGATION_DURATION',
            'width' => '10%',
            'default' => true,
            'name' => 'delegation_duration',
         ),
      ),
   ),
   'templateMeta' => array(
      'maxColumns' => '3',
      'maxColumnsBasic' => '4',
      'widths' => array(
         'label' => '10',
         'field' => '30',
      ),
   ),
);
