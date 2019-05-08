<?php

$searchdefs ['WorkingMonths'] = array(
   'layout' =>
   array(
      'basic_search' =>
      array(
         'name' =>
         array(
            'name' => 'name',
            'default' => true,
            'width' => '10%',
         ),
         'year' =>
         array(
            'type' => 'int',
            'label' => 'LBL_YEAR',
            'width' => '10%',
            'default' => true,
            'name' => 'year',
         ),
         'months' =>
         array(
            'type' => 'enum',
            'default' => true,
            'studio' => 'visible',
            'label' => 'LBL_MONTHS',
            'width' => '10%',
            'name' => 'months',
         ),
      ),
      'advanced_search' =>
      array(
         'name' =>
         array(
            'name' => 'name',
            'default' => true,
            'width' => '10%',
         ),
         'year' =>
         array(
            'type' => 'int',
            'label' => 'LBL_YEAR',
            'width' => '10%',
            'default' => true,
            'name' => 'year',
         ),
         'months' =>
         array(
            'type' => 'enum',
            'default' => true,
            'studio' => 'visible',
            'label' => 'LBL_MONTHS',
            'width' => '10%',
            'name' => 'months',
         ),
         'working_days' =>
         array(
            'type' => 'int',
            'label' => 'LBL_WORKING_DAYS',
            'width' => '10%',
            'default' => true,
            'name' => 'working_days',
         ),
         'working_hours' =>
         array(
            'type' => 'int',
            'label' => 'LBL_WORKING_HOURS',
            'width' => '10%',
            'default' => true,
            'name' => 'working_hours',
         ),
      ),
   ),
   'templateMeta' =>
   array(
      'maxColumns' => '3',
      'maxColumnsBasic' => '4',
      'widths' =>
      array(
         'label' => '10',
         'field' => '30',
      ),
   ),
);
