<?php

$module_name = 'Applications';
$searchdefs [$module_name] = array(
   'layout' =>
   array(
      'basic_search' =>
      array(
         0 =>
         array(
            'name' => 'search_name',
            'label' => 'LBL_NAME',
            'type' => 'name',
         ),
         1 =>
         array(
            'name' => 'current_user_only',
            'label' => 'LBL_CURRENT_USER_FILTER',
            'type' => 'bool',
         ),
         2 =>
         array(
            'name' => 'favorites_only',
            'label' => 'LBL_FAVORITES_FILTER',
            'type' => 'bool',
         ),
      ),
      'advanced_search' =>
      array(
         'search_name' =>
         array(
            'label' => 'LBL_NAME',
            'type' => 'name',
            'width' => '10%',
            'default' => true,
            'name' => 'search_name',
         ),
         'date_modified' =>
         array(
            'type' => 'datetime',
            'label' => 'LBL_DATE_MODIFIED',
            'width' => '10%',
            'default' => true,
            'name' => 'date_modified',
         ),
         'date_entered' =>
         array(
            'type' => 'datetime',
            'label' => 'LBL_DATE_ENTERED',
            'width' => '10%',
            'default' => true,
            'name' => 'date_entered',
         ),
         'modified_user_id' =>
         array(
            'type' => 'assigned_user_name',
            'label' => 'LBL_MODIFIED',
            'width' => '10%',
            'default' => true,
            'name' => 'modified_user_id',
         ),
         'created_by' =>
         array(
            'type' => 'assigned_user_name',
            'label' => 'LBL_CREATED',
            'width' => '10%',
            'default' => true,
            'name' => 'created_by',
         ),
         'status' =>
         array(
            'name' => 'status',
            'default' => true,
            'width' => '10%',
         ),
         'type' =>
         array(
            'name' => 'type',
            'default' => true,
            'width' => '10%',
         ),
         'employee_name' => array(
            'type' => 'relate',
            'link' => true,
            'label' => 'LBL_EMPLOYEE',
            'id' => 'EMPLOYEE_ID',
            'width' => '10%',
            'default' => true,
            'name' => 'employee_name',
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
