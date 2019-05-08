<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

global $current_user;

$dashletData['ReservationsDashlet']['searchFields'] = array(
   'date_entered' => array('default' => ''),
   'date_modified' => array('default' => ''),
   'employee_name' => array('default' => ''),
   'resource_name' => array('default' => ''),
   'DELEGATION_NAME' => array('default' => ''),
   'assigned_user_id' => array(
      'type' => 'assigned_user_name',
      'default' => $current_user->name
   )
);
$dashletData['ReservationsDashlet']['columns'] = array(
   'name' => array(
      'width' => '40',
      'label' => 'LBL_LIST_NAME',
      'link' => true,
      'default' => true
   ),
   'date_entered' => array(
      'width' => '15',
      'label' => 'LBL_DATE_ENTERED',
      'default' => true
   ),
   'date_modified' => array(
      'width' => '15',
      'label' => 'LBL_DATE_MODIFIED'
   ),
   'created_by' => array(
      'width' => '8',
      'label' => 'LBL_CREATED'
   ),
   'assigned_user_name' => array(
      'width' => '8',
      'label' => 'LBL_LIST_ASSIGNED_USER'
   ),
   'employee_name' => array(
      'width' => '15',
      'label' => 'LBL_EMPLOYEE',
      'default' => false
   ),
   'resource_name' => array(
      'width' => '15',
      'label' => 'LBL_RESOURCES',
      'default' => false
   ),
   'parent_name' => array(
      'width' => '25',
      'label' => 'LBL_PARENT_NAME',
      'sortable' => false,
      'dynamic_module' => 'PARENT_TYPE',
      'id' => 'PARENT_ID',
      'link' => true,
      'ACLTag' => 'PARENT',
      'related_fields' => array('parent_id', 'parent_type'),
      'default' => true,
   ),
   'DELEGATION_NAME' => array(
      'width' => '15',
      'label' => 'LBL_DELEGATION',
      'default' => false
   ),
);
