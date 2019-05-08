<?php

$module_name = 'ScheduleReports';
$listViewDefs [$module_name] = array(
   'NAME' =>
   array(
      'width' => '32%',
      'label' => 'LBL_NAME',
      'default' => true,
      'link' => true,
   ),
   'ACTIVE' =>
   array(
      'type' => 'bool',
      'default' => true,
      'label' => 'LBL_ACTIVE',
      'width' => '10%',
   ),
   'FREQUENCY_PERFORMANCE' =>
   array(
      'type' => 'enum',
      'default' => true,
      'studio' => 'visible',
      'label' => 'LBL_FREQUENCY_PERFORMANCE',
      'width' => '10%',
   ),
   'KREPORT_NAME' =>
   array(
      'type' => 'relate',
      'link' => true,
      'label' => 'LBL_SCHEDULEREPORTS_KREPORTS_FROM_KREPORTS_TITLE',
      'id' => 'KREPORT_ID',
      'width' => '10%',
      'default' => true,
   ),
   'DESCRIPTION' =>
   array(
      'type' => 'text',
      'label' => 'LBL_DESCRIPTION',
      'sortable' => false,
      'width' => '10%',
      'default' => false,
   ),
   'TEMPLATE_ID' =>
   array(
      'type' => 'enum',
      'default' => false,
      'studio' => 'visible',
      'label' => 'LBL_TEMPLATE_ID',
      'width' => '10%',
   ),
);
?>
