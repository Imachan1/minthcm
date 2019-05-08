<?php

$popupMeta = array(
   'moduleMain' => 'Positions',
   'varName' => 'Positions',
   'orderBy' => 'positions.name',
   'whereClauses' => array(
      'name' => 'positions.name',
      'department' => 'positions.department',
      'assigned_user_id' => 'positions.assigned_user_id',
      'favorites_only' => 'positions.favorites_only',
      'positions_supervision_name' => 'positions.positions_supervision_name',
   ),
   'searchInputs' => array(
      'name',
      'department',
      'assigned_user_id',
      'favorites_only',
      'positions_supervision_name',
   ),
   'searchdefs' => array(
      'name' =>
      array(
         'name' => 'name',
      ),
      'status' => array(
         'name' => 'status',
         'type' => 'enum',
         'label' => 'LBL_STATUS',
      ),
      'positions_supervision_name' => array(
         'type' => 'relate',
         'link' => true,
         'label' => 'LBL_POSITIONS_SUPERVISION_NAME',
         'id' => 'POSITIONS_SUPERVISION_ID',
         'width' => '10%',
         'name' => 'positions_supervision_name',
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
      ),
      'favorites_only' =>
      array(
         'name' => 'favorites_only',
         'label' => 'LBL_FAVORITES_FILTER',
         'type' => 'bool',
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
      'STATUS' =>
      array(
         'width' => '32%',
         'label' => 'LBL_STATUS',
         'default' => true,
         'name' => 'status',
      ),
      'POSITIONS_SUPERVISION_NAME' => array(
         'type' => 'relate',
         'link' => true,
         'label' => 'LBL_POSITIONS_SUPERVISION_NAME',
         'id' => 'POSITIONS_SUPERVISION_ID',
         'width' => '10%',
         'name' => 'positions_supervision_name',
         'default' => true,
      ),
      'ASSIGNED_USER_NAME' =>
      array(
         'type' => 'relate',
         'label' => 'LBL_ASSIGNED_TO_NAME',
         'id' => 'ASSIGNED_USER_ID',
         'link' => true,
         'sortable' => false,
         'default' => true,
      ),
   ),
);
