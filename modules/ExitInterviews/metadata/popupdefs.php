<?php

$popupMeta = array(
   'moduleMain' => 'ExitInterviews',
   'varName' => 'ExitInterviews',
   'orderBy' => 'exitinterviews.name',
   'whereClauses' => array(
      'name' => 'exitinterviews.name',
      'date_start' => 'exitinterviews.date_start',
      'date_end' => 'exitinterviews.date_end',
      'status' => 'exitinterviews.status',
      'assigned_user_id' => 'exitinterviews.assigned_user_id',
   ),
   'searchInputs' => array(
      'name',
      'status',
      'date_start',
      'date_end',
      'assigned_user_id',
   ),
   'searchdefs' => array(
      'name' =>
      array(
         'name' => 'name',
         'width' => '10%',
      ),
      'date_start' =>
      array(
         'type' => 'datetimecombo',
         'label' => 'LBL_DATE_START',
         'width' => '10%',
         'name' => 'date_start',
      ),
      'date_end' =>
      array(
         'type' => 'datetimecombo',
         'label' => 'LBL_DATE_END',
         'width' => '10%',
         'name' => 'date_end',
      ),
      'status' =>
      array(
         'type' => 'enum',
         'studio' => 'visible',
         'label' => 'LBL_STATUS',
         'width' => '10%',
         'name' => 'status',
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
