<?php

$searchdefs['NonWorkingDays'] = array(
   'layout' => array(
      'basic_search' => array(
         'name' => array(
            'name' => 'name',
            'default' => true,
            'width' => '10%'
         ),
         'date',
      ),
      'advanced_search' => array(
         'name' => array(
            'name' => 'name',
            'default' => true,
            'width' => '10%'
         ),
         'date',
      ),
   ),
   'templateMeta' => array(
      'maxColumns' => '3',
      'maxColumnsBasic' => '4',
      'widths' => array(
         'label' => '10',
         'field' => '30'
      ),
   )
);
