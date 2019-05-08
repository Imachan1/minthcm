<?php

$module_name = 'Transportations';
$listViewDefs [$module_name] = array(
   'NAME' => array(
      'width' => '32%',
      'label' => 'LBL_NAME',
      'default' => true,
      'link' => true,
   ),
   'ASSIGNED_USER_NAME' => array(
      'width' => '9%',
      'label' => 'LBL_ASSIGNED_TO_NAME',
      'module' => 'Employees',
      'id' => 'ASSIGNED_USER_ID',
      'default' => true,
   ),
   'DELEGATION_NAME' => array(
      'type' => 'relate',
      'related_fields' => array(
         'delegation_id',
      ),
      'link' => 'delegations',
      'label' => 'LBL_DELEGATION_NAME',
      'width' => '10%',
      'default' => true,
   ),
   'FROM_CITY' => array(
      'type' => 'varchar',
      'label' => 'LBL_FROM_CITY',
      'width' => '10%',
      'default' => true,
   ),
   'TO_CITY' => array(
      'type' => 'varchar',
      'label' => 'LBL_TO_CITY',
      'width' => '10%',
      'default' => true,
   ),
   'TRANS_DATE' => array(
      'type' => 'date',
      'label' => 'LBL_TRANS_DATE',
      'width' => '10%',
      'default' => true,
   ),
   'TYPE' => array(
      'type' => 'enum',
      'default' => true,
      'studio' => 'visible',
      'label' => 'LBL_TYPE',
      'width' => '10%',
   ),
   'DATE_ENTERED' => array(
      'type' => 'datetime',
      'label' => 'LBL_DATE_ENTERED',
      'width' => '10%',
      'default' => false,
   ),
   'OTHER_TRANSPORTATION' => array(
      'type' => 'varchar',
      'label' => 'LBL_OTHER_TRANSPORTATION',
      'width' => '10%',
      'default' => false,
   ),
   'DATE_MODIFIED' => array(
      'type' => 'datetime',
      'label' => 'LBL_DATE_MODIFIED',
      'width' => '10%',
      'default' => false,
   ),
);
