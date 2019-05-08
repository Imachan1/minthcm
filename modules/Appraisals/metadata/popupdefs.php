<?php

$popupMeta = array(
   'moduleMain' => 'Appraisals',
   'varName' => 'Appraisals',
   'orderBy' => 'appraisals.name',
   'whereClauses' => array(
      'name' => 'appraisals.name',
      'date_end' => 'appraisals.date',
      'type' => 'appraisals.type',
      'status' => 'appraisals.status',
      'status' => 'appraisals.status',
      'assigned_user_id' => 'appraisals.assigned_user_id',
   ),
   'searchInputs' => array(
      'name',
      'status',
      'type',
      'date',
      'assigned_user_id',
   ),
   'searchdefs' => array(
      'name' =>
      array(
         'name' => 'name',
         'width' => '10%',
      ),
      'date' =>
      array(
         'type' => 'date',
         'label' => 'LBL_DATE',
         'width' => '10%',
         'name' => 'date',
      ),
      'candidature_name' =>
      array(
         'name' => 'candidature_name',
         'label' => 'LBL_CANDIDATURE_NAME',
         'type' => 'relate',
         'default' => true,
         'width' => '10%',
      ),
      'type' =>
      array(
         'type' => 'enum',
         'studio' => 'visible',
         'label' => 'LBL_TYPE',
         'width' => '10%',
         'name' => 'type',
      ),
      'employee_name' =>
      array(
         'name' => 'employee_name',
         'label' => 'LBL_EMPLOYEE_NAME',
         'type' => 'relate',
         'default' => true,
         'width' => '10%',
      ),
      'status' =>
      array(
         'type' => 'enum',
         'studio' => 'visible',
         'label' => 'LBL_STATUS',
         'width' => '10%',
         'name' => 'status',
      ),
      'position_name' =>
      array(
         'name' => 'position_name',
         'label' => 'LBL_POSITION_NAME',
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
         'width' => '10%',
      ),
   ),
);
