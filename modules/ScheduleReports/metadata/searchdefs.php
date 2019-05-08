<?php

$module_name = 'ScheduleReports';
$searchdefs [$module_name] = array(
   'layout' =>
   array(
      'basic_search' =>
      array(
         'name',
      ),
      'advanced_search' =>
      array(
         'name' =>
         array(
            'name' => 'name',
            'default' => true,
            'width' => '10%',
         ),
         'frequency_performance' =>
         array(
            'type' => 'enum',
            'default' => true,
            'studio' => 'visible',
            'label' => 'LBL_FREQUENCY_PERFORMANCE',
            'width' => '10%',
            'name' => 'frequency_performance',
         ),
         'active' =>
         array(
            'type' => 'bool',
            'default' => true,
            'label' => 'LBL_ACTIVE',
            'width' => '10%',
            'name' => 'active',
         ),
         'kreport_name' =>
         array(
            'type' => 'relate',
            'link' => true,
            'label' => 'LBL_SCHEDULEREPORTS_KREPORTS_FROM_KREPORTS_TITLE',
            'id' => 'KREPORT_ID',
            'width' => '10%',
            'default' => true,
            'name' => 'kreport_name',
         ),
         'template_id' =>
         array(
            'type' => 'enum',
            'default' => true,
            'studio' => 'visible',
            'label' => 'LBL_TEMPLATE_ID',
            'width' => '10%',
            'name' => 'template_id',
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
