<?php

$module_name = 'Resources';
$searchdefs[$module_name] = array(
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
         'type' =>
         array(
            'type' => 'enum',
            'studio' => 'visible',
            'label' => 'LBL_TYPE',
            'width' => '10%',
            'default' => true,
            'name' => 'type',
         ),
         'unavailable' =>
         array(
            'type' => 'bool',
            'default' => true,
            'label' => 'LBL_UNAVAILABLE',
            'width' => '10%',
            'name' => 'unavailable',
         ),
      ),
   ),
);
