<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

$module_name = 'Conclusions';
$object_name = 'Conclusions';
$_module_name = 'conclusions';
$popupMeta = array(
   'moduleMain' => $module_name,
   'varName' => $object_name,
   'orderBy' => $_module_name . '.name',
   'whereClauses' => array(
      'name' => $_module_name . '.name',
      'assigned_user_id' => $_module_name . '.assigned_user_id',
      'unavailable' => $_module_name . '.unavailable',
      'type' => $_module_name . '.type',
      'meeting_name' => 'meetings.meeting_name',
   ),
   'searchInputs' => array( $_module_name . 'name', 'assigned_user_id', 'unavailable', 'type', 'meeting_name' ),
   'whereStatement' => "",
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
      'meeting_name' => array(
         'type' => 'relate',
         'link' => true,
         'label' => 'LBL_MEETING_NAME',
         'id' => 'MEETING_ID',
         'width' => '10%',
         'name' => 'meeting_name',
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
      ),
      'MEETING_NAME' => array(
         'type' => 'relate',
         'link' => true,
         'label' => 'LBL_MEETING_NAME',
         'id' => 'MEETING_ID',
         'width' => '10%',
         'default' => true,
         'name' => 'meeting_name',
      ),
   )
);
