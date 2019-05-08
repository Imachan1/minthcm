<?php

$module_name = 'Conclusions';
$searchdefs[$module_name] = array(
   'templateMeta' => array(
      'maxColumns' => '3',
      'maxColumnsBasic' => '4',
      'widths' => array( 'label' => '10', 'field' => '30' ),
   ),
   'layout' => array(
      'basic_search' => array(
         'name',
         array( 'name' => 'current_user_only', 'label' => 'LBL_CURRENT_USER_FILTER', 'type' => 'bool' ),
      ),
      'advanced_search' => array(
         'name',
         array(
            'name' => 'assigned_user_id',
            'label' => 'LBL_ASSIGNED_TO',
            'type' => 'enum',
            'function' => array( 'name' => 'get_user_array', 'params' => array( false ) )
         ),
         'meeting_name' => array(
            'type' => 'relate',
            'link' => true,
            'label' => 'LBL_MEETING_NAME',
            'id' => 'MEETING_ID',
            'width' => '10%',
            'default' => true,
            'name' => 'meeting_name',
         ),
         'date_entered' =>
         array(
            'type' => 'datetime',
            'label' => 'LBL_DATE_ENTERED',
            'width' => '10%',
            'default' => true,
            'name' => 'date_entered',
         ),
         'date_modified' =>
         array(
            'type' => 'datetime',
            'label' => 'LBL_DATE_MODIFIED',
            'width' => '10%',
            'default' => true,
            'name' => 'date_modified',
         ),
         'created_by' =>
         array(
            'type' => 'assigned_user_name',
            'label' => 'LBL_CREATED',
            'width' => '10%',
            'default' => true,
            'name' => 'created_by',
         ),
         'modified_user_id' =>
         array(
            'type' => 'assigned_user_name',
            'label' => 'LBL_MODIFIED',
            'width' => '10%',
            'default' => true,
            'name' => 'modified_user_id',
         ),
      ),
   ),
);
