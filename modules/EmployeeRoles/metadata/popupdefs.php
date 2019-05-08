<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

$module_name = 'EmployeeRoles';
$object_name = 'EmployeeRoles';
$_module_name = 'employeeroles';
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
   'whereStatement' => "",
   'searchdefs' => array(
      'name' =>
      array(
         'name' => 'name',
         'width' => '10%',
      ),
      'status' => array(
         'name' => 'status',
         'type' => 'enum',
         'label' => 'LBL_STATUS',
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
      'STATUS' =>
      array(
         'width' => '32%',
         'label' => 'LBL_STATUS',
         'default' => true,
         'name' => 'status',
      ),
      'ASSIGNED_USER_NAME' =>
      array(
         'width' => '9%',
         'label' => 'LBL_ASSIGNED_TO_NAME',
         'module' => 'Employees',
         'id' => 'ASSIGNED_USER_ID',
         'default' => true,
      ),
   )
);
