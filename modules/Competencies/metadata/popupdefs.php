<?php

$popupMeta = array(
   'moduleMain' => 'Competencies',
   'varName' => 'Competencies',
   'orderBy' => 'competencies.name',
   'whereClauses' => array(
      'name' => 'competencies.name',
      'date_entered' => 'competencies.date_entered',
      'date_modified' => 'competencies.date_modified',
      'assigned_user_name' => 'competencies.assigned_user_name',
   ),
   'searchInputs' => array(
      'name',
      'date_entered',
      'date_modified',
      'assigned_user_name',
   ),
   'searchdefs' => array(
      'name' =>
      array(
         'name' => 'name',
         'width' => '10%',
      ),
      'date_entered' =>
      array(
         'type' => 'datetime',
         'label' => 'LBL_DATE_ENTERED',
         'width' => '10%',
         'name' => 'date_entered',
      ),
      'date_modified' =>
      array(
         'type' => 'datetime',
         'label' => 'LBL_DATE_MODIFIED',
         'width' => '10%',
         'name' => 'date_modified',
      ),
      'assigned_user_name' =>
      array(
         'link' => true,
         'type' => 'relate',
         'label' => 'LBL_ASSIGNED_TO_NAME',
         'id' => 'ASSIGNED_USER_ID',
         'width' => '10%',
         'name' => 'assigned_user_name',
      ),
   ),
);
