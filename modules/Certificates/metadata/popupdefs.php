<?php

$popupMeta = array(
   'moduleMain' => 'Certificates',
   'varName' => 'Certificates',
   'orderBy' => 'certificates.name',
   'whereClauses' => array(
      'name' => 'certificates.name',
      'start_date' => 'certificates.start_date',
      'end_date' => 'certificates.end_date',
      'status' => 'certificates.status',
      'candidate_name' => 'candidates.candidate_name',
   ),
   'searchInputs' => array(
      'name',
      'start_date',
      'end_date',
      'status',
      'candidate_name',
   ),
   'searchdefs' => array(
      'name' => array(
         'name' => 'name',
      ),
      'start_date' => array(
         'name' => 'start_date',
      ),
      'end_date' => array(
         'name' => 'end_date',
      ),
      'status' => array(
         'name' => 'status',
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
      'candidate_name' => array(
         'type' => 'relate',
         'link' => true,
         'label' => 'LBL_RELATIONSHIP_CANDIDATE_NAME',
         'id' => 'CANDIDATE_ID',
         'width' => '10%',
         'name' => 'candidate_name',
      ),
   ),
   'listviewdefs' => array(
      'NAME' => array(
         'label' => 'LBL_NAME',
         'link' => true,
         'default' => true,
      ),
      'start_date' => array(
         'default' => true,
         'label' => 'LBL_START_DATE',
         'name' => 'start_date',
      ),
      'end_date' => array(
         'default' => true,
         'label' => 'LBL_END_DATE',
         'name' => 'end_date',
      ),
      'status' => array(
         'default' => true,
         'label' => 'LBL_STATUS',
         'name' => 'status',
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
      'CANDIDATE_NAME' => array(
         'type' => 'relate',
         'link' => true,
         'label' => 'LBL_RELATIONSHIP_CANDIDATE_NAME',
         'id' => 'CANDIDATE_ID',
         'width' => '10%',
         'default' => true,
         'name' => 'candidate_name',
      ),
   ),
);
