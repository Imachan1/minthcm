<?php

$popupMeta = array(
   'moduleMain' => 'Recruitments',
   'varName' => 'Recruitments',
   'orderBy' => 'recruitments.name',
   'whereClauses' => array(
      'name' => 'recruitments.name',
      'position_name' => 'recruitments.position_name',
      'project_status' => 'recruitments.project_status',
      'start_date' => 'recruitments.start_date',
      'start_work_date' => 'recruitments.start_work_date',
      'end_date' => 'recruitments.end_date',
      'assigned_user_id' => 'recruitments.assigned_user_id',
      'favorites_only' => 'recruitments.favorites_only',
      'vacancy' => 'recruitments.vacancy',
   ),
   'searchInputs' => array(
      'name',
      'position_name',
      'project_status',
      'start_date',
      'start_work_date',
      'end_date',
      'assigned_user_id',
      'favorites_only',
      'vacancy',
   ),
   'searchdefs' => array(
      'name' =>
      array(
         'name' => 'name',
      ),
      'position_name' => array(
         'type' => 'relate',
         'link' => true,
         'label' => 'LBL_RECRUITMENTS_POSITIONS_FROM_POSITIONS_TITLE',
         'id' => 'position_id',
         'sortable' => false,
         'name' => 'position_name',
      ),
      'project_status' => array(
         'type' => 'enum',
         'studio' => 'visible',
         'label' => 'LBL_PROJECT_STATUS',
         'name' => 'project_status',
      ),
      'start_date' => array(
         'type' => 'date',
         'label' => 'LBL_START_DATE',
         'name' => 'start_date',
      ),
      'start_work_date' => array(
         'type' => 'date',
         'label' => 'LBL_START_WORK_DATE',
         'name' => 'start_work_date',
      ),
      'end_date' => array(
         'type' => 'date',
         'label' => 'LBL_END_DATE',
         'name' => 'end_date',
      ),
      'assigned_user_id' => array(
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
      ),
      'favorites_only' => array(
         'name' => 'favorites_only',
         'label' => 'LBL_FAVORITES_FILTER',
         'type' => 'bool',
      ),
      'vacancy' => array(
         'type' => 'int',
         'label' => 'LBL_VACANCY',
         'name' => 'vacancy',
      ),
   ),
   'listviewdefs' => array(
      'NAME' =>
      array(
         'label' => 'LBL_NAME',
         'default' => true,
         'link' => true,
         'name' => 'name',
      ),
      'POSITION_NAME' => array(
         'type' => 'relate',
         'link' => true,
         'label' => 'LBL_RECRUITMENTS_POSITIONS_FROM_POSITIONS_TITLE',
         'id' => 'position_id',
         'sortable' => false,
         'default' => true,
         'name' => 'position_name',
      ),
      'PROJECT_STATUS' => array(
         'type' => 'enum',
         'default' => true,
         'studio' => 'visible',
         'label' => 'LBL_PROJECT_STATUS',
         'name' => 'project_status',
      ),
      'START_DATE' => array(
         'type' => 'date',
         'default' => true,
         'label' => 'LBL_START_DATE',
         'name' => 'start_date',
      ),
      'END_DATE' => array(
         'type' => 'date',
         'default' => true,
         'label' => 'LBL_END_DATE',
         'name' => 'end_date',
      ),
      'ASSIGNED_USER_ID' => array(
         'type' => 'relate',
         'label' => 'LBL_ASSIGNED_TO_ID',
         'id' => 'ASSIGNED_USER_ID',
         'link' => true,
         'sortable' => false,
         'default' => true,
      ),
      'VACANCY' => array(
         'type' => 'int',
         'default' => true,
         'label' => 'LBL_VACANCY',
      ),
   ),
);
