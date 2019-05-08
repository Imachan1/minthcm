<?php

$popupMeta = array(
   'moduleMain' => 'Trainings',
   'varName' => 'Trainings',
   'orderBy' => 'trainings.name',
   'whereClauses' => array(
      'name' => 'trainings.name',
      'date_start' => 'trainings.date_start',
      'date_end' => 'trainings.date_end',
      'status' => 'trainings.status',
      'training_type' => 'trainings.training_type',
      'assigned_user_id' => 'trainings.assigned_user_id',
   ),
   'searchInputs' => array(
      'name',
      'status',
      'date_start',
      'date_end',
      'training_type',
      'assigned_user_id',
   ),
   'searchdefs' => array(
      'name' =>
      array(
         'name' => 'name',
         'width' => '10%',
      ),
      'date_start' =>
      array(
         'type' => 'datetimecombo',
         'label' => 'LBL_DATE_START',
         'width' => '10%',
         'name' => 'date_start',
      ),
      'date_end' =>
      array(
         'type' => 'datetimecombo',
         'label' => 'LBL_DATE_END',
         'width' => '10%',
         'name' => 'date_end',
      ),
      'status' =>
      array(
         'type' => 'enum',
         'studio' => 'visible',
         'label' => 'LBL_STATUS',
         'width' => '10%',
         'name' => 'status',
      ),
      'training_type' =>
      array(
         'type' => 'enum',
         'studio' => 'visible',
         'label' => 'LBL_TRAINING_TYPE',
         'width' => '10%',
         'name' => 'training_type',
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
      'parent_name' => array(
         'type' => 'parent',
         'link' => true,
         'label' => 'LBL_PARENT_NAME',
         'id' => 'PARENT_ID',
         'width' => '10%',
         'name' => 'parent_name',
      ),
   ),
);
