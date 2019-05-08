<?php

$popupMeta = array(
   'moduleMain' => 'WorkingMonths',
   'varName' => 'WorkingMonths',
   'orderBy' => 'workingmonths.name',
   'whereClauses' => array(
      'year' => 'workingmonths.year',
      'months' => 'workingmonths.months',
   ),
   'searchInputs' => array(
      4 => 'year',
      5 => 'months',
   ),
   'searchdefs' => array(
      'year' =>
      array(
         'type' => 'int',
         'label' => 'LBL_YEAR',
         'width' => '10%',
         'name' => 'year',
      ),
      'months' =>
      array(
         'type' => 'enum',
         'studio' => 'visible',
         'label' => 'LBL_MONTHS',
         'width' => '10%',
         'name' => 'months',
      ),
   ),
   'listviewdefs' => array(
      'NAME' =>
      array(
         'type' => 'name',
         'link' => true,
         'label' => 'LBL_NAME',
         'width' => '10%',
         'default' => true,
      ),
      'WORKING_HOURS' =>
      array(
         'type' => 'int',
         'label' => 'LBL_WORKING_HOURS',
         'width' => '10%',
         'default' => true,
      ),
      'WORKING_DAYS' =>
      array(
         'type' => 'int',
         'label' => 'LBL_WORKING_DAYS',
         'width' => '10%',
         'default' => true,
      ),
   ),
);
