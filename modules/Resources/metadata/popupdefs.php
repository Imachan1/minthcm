<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

$module_name = 'Resources';
$object_name = 'Resources';
$_module_name = 'resources';
$popupMeta = array(
   'moduleMain' => $module_name,
   'varName' => $object_name,
   'orderBy' => $_module_name . '.name',
   'whereClauses' => array(
      'name' => $_module_name . '.name',
      'assigned_user_id' => $_module_name . '.assigned_user_id',
      'unavailable' => $_module_name . '.unavailable',
      'type' => $_module_name . '.type',
   ),
   'searchInputs' => array($_module_name . 'name', 'assigned_user_id', 'unavailable', 'type'),
   'whereStatement' => "type='for_reservation'",
   'searchdefs' => array(
      'name' =>
      array(
         'name' => 'name',
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
      'unavailable' =>
      array(
         'type' => 'bool',
         'label' => 'LBL_UNAVAILABLE',
         'width' => '10%',
         'name' => 'unavailable',
      ),
   ),
   'listviewdefs' => array(
      'NAME' =>
      array(
         'width' => '32%',
         'label' => 'LBL_NAME',
         'default' => true,
         'link' => true,
         'name' => 'name',
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
      'UNAVAILABLE' =>
      array(
         'type' => 'bool',
         'default' => true,
         'label' => 'LBL_UNAVAILABLE',
         'width' => '10%',
         'name' => 'unavailable',
      ),
      'TYPE' =>
      array(
         'type' => 'enum',
         'studio' => 'visible',
         'label' => 'LBL_TYPE',
         'width' => '10%',
         'default' => true,
         'name' => 'type',
      ),
   ),
);
