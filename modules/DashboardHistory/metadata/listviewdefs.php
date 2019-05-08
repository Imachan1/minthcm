<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

$module_name = 'DashboardHistory';
$listViewDefs[$module_name] = array(
   'NAME' => array(
      'width' => '32',
      'label' => 'LBL_NAME',
      'default' => true,
      'link' => true
   ),
   'ASSIGNED_USER_NAME' => array(
      'width' => '9',
      'label' => 'LBL_ASSIGNED_TO_NAME',
      'module' => 'Employees',
      'id' => 'ASSIGNED_USER_ID',
      'default' => true
   ),
   'DASHBOARDMANAGER_NAME' => array(
      'width' => '9',
      'label' => 'LBL_DASHBOARDMANAGER_NAME',
      'module' => 'DashboardManager',
      'id' => 'DASHBOARDMANAGER_ID',
      'default' => true
   ),
   'DATE_ENTERED' =>
   array(
      'type' => 'datetime',
      'label' => 'LBL_DATE_ENTERED',
      'width' => '20%',
      'default' => true,
   ),
   'CREATED_BY_NAME' =>
   array(
      'type' => 'relate',
      'link' => true,
      'label' => 'LBL_CREATED',
      'id' => 'CREATED_BY',
      'width' => '10%',
      'default' => true,
   ),
   'DATE_MODIFIED' =>
   array(
      'type' => 'datetime',
      'label' => 'LBL_DATE_MODIFIED',
      'width' => '10%',
      'default' => false,
   ),
   'MODIFIED_BY_NAME' =>
   array(
      'type' => 'relate',
      'link' => true,
      'label' => 'LBL_MODIFIED_NAME',
      'id' => 'MODIFIED_USER_ID',
      'width' => '10%',
      'default' => false,
   ),
);
