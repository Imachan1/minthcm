<?php

$popupMeta = array(
   'moduleMain' => 'Delegations',
   'varName' => 'Delegations',
   'orderBy' => 'delegations.name',
   'whereClauses' => array(
      'assigned_user_name' => 'delegations.assigned_user_name',
      'start_date' => 'delegations.start_date',
      'end_date' => 'delegations.end_date',
   ),
   'searchInputs' => array(
      'assigned_user_name',
      'start_date',
      'end_date',
   ),
   'searchdefs' => array(
      'assigned_user_name' => array(
         'link' => 'assigned_user_link',
         'type' => 'relate',
         'label' => 'LBL_ASSIGNED_TO_NAME',
         'width' => '10%',
         'name' => 'assigned_user_name',
      ),
      'start_date' => array(
         'type' => 'datetimecombo',
         'label' => 'LBL_START_DATE',
         'width' => '10%',
         'name' => 'start_date',
      ),
      'end_date' => array(
         'type' => 'datetimecombo',
         'label' => 'LBL_END_DATE',
         'width' => '10%',
         'name' => 'end_date',
      ),
   ),
   'listviewdefs' => array(
      'NAME' => array(
         'type' => 'varchar',
         'label' => 'LBL_NAME',
         'width' => '10%',
         'default' => true,
         'link' => true,
      ),
      'ASSIGNED_USER_NAME' => array(
         'link' => 'assigned_user_link',
         'type' => 'relate',
         'label' => 'LBL_ASSIGNED_TO_NAME',
         'width' => '10%',
         'default' => true,
         'name' => 'assigned_user_name',
      ),
      'START_DATE' => array(
         'type' => 'datetimecombo',
         'label' => 'LBL_START_DATE',
         'width' => '10%',
         'default' => true,
         'name' => 'start_date',
      ),
      'END_DATE' => array(
         'type' => 'datetimecombo',
         'label' => 'LBL_END_DATE',
         'width' => '10%',
         'default' => true,
         'name' => 'end_date',
      ),
   ),
);
