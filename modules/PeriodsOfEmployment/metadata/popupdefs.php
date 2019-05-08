<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

$module_name = 'PeriodsOfEmployment';
$object_name = 'PeriodsOfEmployment';
$_module_name = 'periodsofemployment';
$popupMeta = array(
   'moduleMain' => $module_name,
   'varName' => $object_name,
   'orderBy' => $_module_name . '.name',
   'whereClauses' => array(
      'name' => $_module_name . '.name',
      'date_modified' => $_module_name . '.date_modified',
      'assigned_user_id' => $_module_name . '.assigned_user_id',
      'date_entered' => $_module_name . '.date_entered',
      'period_starting_date_c' => $_module_name . '.period_starting_date_c',
      'period_ending_date_c' => $_module_name . '.period_ending_date_c',
   ),
   'searchInputs' => array(
      1 => 'name',
      4 => 'date_modified',
      5 => 'assigned_user_id',
      6 => 'date_entered',
      7 => 'period_starting_date_c',
      8 => 'period_ending_date_c',
   ),
   'searchdefs' => array(
      'name' =>
      array(
         'name' => 'name',
         'width' => '10%',
      ),
      'date_modified' =>
      array(
         'type' => 'datetime',
         'label' => 'LBL_DATE_MODIFIED',
         'width' => '10%',
         'name' => 'date_modified',
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
      'date_entered' =>
      array(
         'type' => 'datetime',
         'label' => 'LBL_DATE_ENTERED',
         'width' => '10%',
         'name' => 'date_entered',
      ),
      'period_starting_date_c' =>
      array(
         'type' => 'date',
         'label' => 'LBL_PERIOD_STARTING_DATE',
         'width' => '10%',
         'name' => 'period_starting_date_c',
      ),
      'period_ending_date_c' =>
      array(
         'type' => 'date',
         'label' => 'LBL_PERIOD_ENDING_DATE',
         'width' => '10%',
         'name' => 'period_ending_date_c',
      ),
   ),
);
