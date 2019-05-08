<?php

$listViewDefs['WorkingMonths'] = array(
   'NAME' => array(
      'width' => '32%',
      'label' => 'LBL_NAME',
      'default' => true,
      'link' => true,
   ),
   'WORKING_DAYS' => array(
      'type' => 'int',
      'label' => 'LBL_WORKING_DAYS',
      'width' => '10%',
      'default' => true,
   ),
   'WORKING_HOURS' => array(
      'type' => 'int',
      'label' => 'LBL_WORKING_HOURS',
      'width' => '10%',
      'default' => true,
   ),
   'ASSIGNED_USER_NAME' => array(
      'width' => '9%',
      'label' => 'LBL_ASSIGNED_TO_NAME',
      'module' => 'Employees',
      'id' => 'ASSIGNED_USER_ID',
      'default' => false,
   ),
   'YEAR' => array(
      'type' => 'int',
      'label' => 'LBL_YEAR',
      'width' => '10%',
      'default' => false,
   ),
   'MONTHS' => array(
      'type' => 'enum',
      'default' => true,
      'studio' => 'visible',
      'label' => 'LBL_MONTHS',
      'width' => '10%',
   ),
);
