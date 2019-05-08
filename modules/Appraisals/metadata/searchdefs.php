<?php

$module_name = 'Appraisals';
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
         'date' =>
         array(
            'type' => 'date',
            'label' => 'LBL_DATE',
            'width' => '10%',
            'default' => true,
            'name' => 'date',
         ),
         'type' =>
         array(
            'type' => 'enum',
            'default' => true,
            'studio' => 'visible',
            'label' => 'LBL_TYPE',
            'width' => '10%',
            'name' => 'type',
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
         'status' =>
         array(
            'type' => 'enum',
            'default' => true,
            'studio' => 'visible',
            'label' => 'LBL_STATUS',
            'width' => '10%',
            'name' => 'status',
         ),
         'candidature_name' =>
         array(
            'name' => 'candidature_name',
            'label' => 'LBL_CANDIDATURE_NAME',
            'type' => 'relate',
            'default' => true,
            'width' => '10%',
         ),
         'position_name' =>
         array(
            'name' => 'position_name',
            'label' => 'LBL_POSITION_NAME',
            'type' => 'relate',
            'default' => true,
            'width' => '10%',
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
;
?>
