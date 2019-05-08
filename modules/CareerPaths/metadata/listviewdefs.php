<?php

$module_name = 'CareerPaths';
$listViewDefs [$module_name] = array(
   'NAME' =>
   array(
      'width' => '32%',
      'label' => 'LBL_NAME',
      'default' => true,
      'link' => true,
   ),
   'POSITION_FROM_NAME' =>
   array(
      'type' => 'relate',
      'label' => 'LBL_POSITION_FROM_NAME',
      'width' => '10%',
      'default' => true,
   ),
   'POSITION_TO_NAME' =>
   array(
      'type' => 'relate',
      'label' => 'LBL_POSITION_TO_NAME',
      'width' => '10%',
      'default' => false,
   ),
   'DATE_MODIFIED' =>
   array(
      'type' => 'datetime',
      'label' => 'LBL_DATE_MODIFIED',
      'width' => '10%',
      'default' => true,
   ),
   'DATE_ENTERED' =>
   array(
      'type' => 'datetime',
      'label' => 'LBL_DATE_ENTERED',
      'width' => '10%',
      'default' => true,
   ),
);
;
?>
