<?php

global $current_user;

$whereStatement = " workschedules.status!='closed' AND workschedules.assigned_user_id='{$current_user->id}' ";
if ( isset($_REQUEST['workschedules_type_advanced']) && $_REQUEST['workschedules_type_advanced'] ) {
   $whereStatement .= " AND workschedules.type IN ('home','delegation') ";
}

$popupMeta = array(
   'moduleMain' => 'workschedules',
   'varName' => 'workschedules',
   'orderBy' => 'workschedules.name',
   'whereStatement' => $whereStatement,
   'whereClauses' => array(
      'schedule_date' => 'workschedules.schedule_date',
      'type' => 'workschedules.type',
      'spent_time' => 'workschedules.spent_time',
      'status' => 'workschedules.status',
   ),
   'searchInputs' => array(
      'status',
      'schedule_date',
      'type',
      'spent_time',
   ),
   'searchdefs' => array(
      'schedule_date' =>
      array(
         'type' => 'date',
         'label' => 'LBL_SCHEDULE_DATE',
         'width' => '10%',
         'name' => 'schedule_date',
      ),
      'type' =>
      array(
         'type' => 'enum',
         'studio' => 'visible',
         'label' => 'LBL_TYPE',
         'width' => '10%',
         'name' => 'type',
      ),
      'spent_time' =>
      array(
         'type' => 'float',
         'label' => 'LBL_SPENT_TIME',
         'width' => '10%',
         'name' => 'spent_time',
      ),
      'status' =>
      array(
         'type' => 'enum',
         'studio' => 'visible',
         'label' => 'LBL_STATUS',
         'width' => '10%',
         'name' => 'status',
      ),
      'supervisor_acceptance' =>
      array(
         'type' => 'enum',
         'studio' => 'visible',
         'label' => 'LBL_SUPERVISOR_ACCEPTANCE',
         'width' => '10%',
         'name' => 'supervisor_acceptance',
      ),
      'workschedules_type' =>
      array(
         'type' => 'varchar',
         'studio' => array( 'editview' => 'false', ),
         'label' => '',
         'width' => '10%',
         'default' => false,
         'name' => 'workschedules_type',
         'displayParams' => array( 'hidden' => true ),
         'basic_search' => true,
      ),
   ),
   'listviewdefs' => array(
      'NAME' =>
      array(
         'type' => 'name',
         'link' => true,
         'label' => 'LBL_NAME',
         'width' => '10%',
         'default' => true,
      ),
      'STATUS' =>
      array(
         'type' => 'enum',
         'default' => true,
         'studio' => 'visible',
         'label' => 'LBL_STATUS',
         'width' => '10%',
      ),
      'SPENT_TIME' =>
      array(
         'type' => 'float',
         'label' => 'LBL_SPENT_TIME',
         'width' => '10%',
         'default' => true,
      ),
   ),
);
echo '<script type="text/javascript">$(document).ready(function(){$("#status_advanced option[value=\'closed\']").remove();});</script>';
