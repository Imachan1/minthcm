<?php

$module_name = 'Offboardings';
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
         'date_start' =>
         array(
            'type' => 'datetimecombo',
            'label' => 'LBL_DATE_START',
            'width' => '10%',
            'default' => true,
            'name' => 'date_start',
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
         array(
            'name' => 'offboardingtemplate_name',
            'label' => 'LBL_OFFBOARDINGTEMPLATE_NAME',
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
