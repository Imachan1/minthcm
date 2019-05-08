<?php

$module_name = 'Certificates';
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
         'status' =>
         array(
            'name' => 'status',
            'default' => true,
            'width' => '10%',
         ),
         'start_date' =>
         array(
            'name' => 'start_date',
            'default' => true,
            'width' => '10%',
         ),
         'end_date' =>
         array(
            'name' => 'end_date',
            'default' => true,
            'width' => '10%',
         ),
         'date_entered' =>
         array(
            'type' => 'datetime',
            'label' => 'LBL_DATE_ENTERED',
            'width' => '10%',
            'default' => true,
            'name' => 'date_entered',
         ),
         'date_modified' =>
         array(
            'type' => 'datetime',
            'label' => 'LBL_DATE_MODIFIED',
            'width' => '10%',
            'default' => true,
            'name' => 'date_modified',
         ),
         'candidate_name' => array(
            'type' => 'relate',
            'link' => true,
            'label' => 'LBL_RELATIONSHIP_CANDIDATE_NAME',
            'id' => 'CANDIDATE_ID',
            'width' => '10%',
            'default' => true,
            'name' => 'candidate_name',
         ),
         'employee_name' =>
         array(
            'name' => 'employee_name',
            'label' => 'LBL_EMPLOYEE_NAME',
            'type' => 'relate',
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
