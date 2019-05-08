<?php

$module_name = 'Reservations';
$searchdefs[$module_name] = array(
   'templateMeta' => array(
      'maxColumns' => '3',
      'maxColumnsBasic' => '4',
      'widths' => array('label' => '10', 'field' => '30'),
   ),
   'layout' => array(
      'basic_search' => array(
         'name',
         array('name' => 'current_user_only', 'label' => 'LBL_CURRENT_USER_FILTER', 'type' => 'bool'),
      ),
      'advanced_search' => array(
         'name',
         array(
            'name' => 'assigned_user_id',
            'label' => 'LBL_ASSIGNED_TO',
            'type' => 'enum',
            'function' => array('name' => 'get_user_array', 'params' => array(false))
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
         'parent_name' =>
         array(
            'type' => 'parent',
            'label' => 'LBL_LIST_RELATED_TO',
            'width' => '10%',
            'default' => true,
            'name' => 'parent_name',
         ),
         'resource_name' =>
         array(
            'type' => 'relate',
            'link' => true,
            'label' => 'LBL_RESOURCES',
            'id' => 'RESOURCES_ID',
            'width' => '10%',
            'default' => true,
            'name' => 'resource_name',
         ),
         'delegation_name' =>
         array(
            'type' => 'relate',
            'link' => true,
            'label' => 'LBL_DELEGATIONS',
            'id' => 'DELEGATIONS_ID',
            'width' => '10%',
            'default' => true,
            'name' => 'delegation_name',
         ),
      ),
   ),
);
