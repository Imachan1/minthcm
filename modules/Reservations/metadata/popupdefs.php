<?php

$popupMeta = array(
   'moduleMain' => 'Reservations',
   'varName' => 'Reservations',
   'orderBy' => 'reservations.name',
   'whereClauses' => array(
      'name' => 'reservations.name',
      'assigned_user_id' => 'reservations.assigned_user_id',
      'starting_date' => 'reservations.starting_date',
      'ending_date' => 'reservations.ending_date',
   ),
   'searchInputs' => array(
      'name',
      'assigned_user_id',
      'starting_date',
      'ending_date',
   ),
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
      'starting_date' =>
      array(
         'type' => 'datetimecombo',
         'label' => 'LBL_STARTING_DATE',
         'width' => '10%',
         'name' => 'starting_date',
      ),
      'ending_date' =>
      array(
         'type' => 'datetimecombo',
         'label' => 'LBL_ENDING_DATE',
         'width' => '10%',
         'name' => 'ending_date',
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
      'STARTING_DATE' =>
      array(
         'type' => 'datetimecombo',
         'label' => 'LBL_STARTING_DATE',
         'width' => '10%',
         'default' => true,
         'name' => 'starting_date',
      ),
      'ENDING_DATE' =>
      array(
         'type' => 'datetimecombo',
         'label' => 'LBL_ENDING_DATE',
         'width' => '10%',
         'default' => true,
         'name' => 'ending_date',
      ),
   ),
);
