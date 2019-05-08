<?php

$popupMeta = array(
   'moduleMain' => 'Applications',
   'varName' => 'Applications',
   'orderBy' => 'applications.name',
   'whereClauses' => array(
      'name' => 'applications.name',
      'start_date' => 'applications.start_date',
      'end_date' => 'applications.end_date',
      'status' => 'applications.status',
   ),
   'searchInputs' => array(
      'name',
      'start_date',
      'end_date',
      'status',
      'type',
   ),
   'searchdefs' => array(
      'name' => array(
         'name' => 'name',
      ),
      'status' => array(
         'name' => 'status',
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
   'listviewdefs' => array(
      'NAME' => array(
         'label' => 'LBL_NAME',
         'link' => true,
         'default' => true,
      ),
      'status' => array(
         'default' => true,
         'label' => 'LBL_STATUS',
         'name' => 'status',
      ),
      'type' => array(
         'default' => true,
         'label' => 'LBL_TYPE',
         'name' => 'type',
      ),
      'ASSIGNED_USER_NAME' =>
      array(
         'width' => '9%',
         'label' => 'LBL_ASSIGNED_TO_NAME',
         'module' => 'Employees',
         'id' => 'ASSIGNED_USER_ID',
         'default' => true,
         'name' => 'assigned_user_name',
      ),
      'EMPLOYEE_NAME' =>
      array(
         'width' => '9%',
         'label' => 'LBL_EMPLOYEE',
         'module' => 'Employees',
         'default' => true,
         'name' => 'employee_name',
      ),
   ),
);
