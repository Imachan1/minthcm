<?php

$module_name = 'DashboardManager';
$listViewDefs [$module_name] = array(
   'NAME' =>
   array(
      'width' => '15%',
      'label' => 'LBL_NAME',
      'default' => true,
      'link' => true,
   ),
   'IS_LOADED' =>
   array(
      'type' => 'bool',
      'label' => 'LBL_IS_LOADED',
      'sortable' => true,
      'width' => '5%',
      'default' => true,
   ),
   'DESCRIPTION' =>
   array(
      'type' => 'text',
      'label' => 'LBL_DESCRIPTION',
      'sortable' => false,
      'width' => '40%',
      'default' => true,
   ),
   'ASSIGNED_USER_NAME' =>
   array(
      'width' => '9%',
      'label' => 'LBL_ASSIGNED_TO_NAME',
      'module' => 'Employees',
      'id' => 'ASSIGNED_USER_ID',
      'default' => false,
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
