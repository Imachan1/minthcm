<?php

$module_name = 'Delegations_locale';
$listViewDefs [$module_name] = array(
   'NAME' => array(
      'width' => '32%',
      'label' => 'LBL_NAME',
      'default' => true,
      'link' => true,
   ),
   'REGIMEN_VALUE' => array(
      'type' => 'currency',
      'label' => 'LBL_REGIMEN_VALUE',
      'currency_format' => true,
      'width' => '10%',
      'default' => true,
   ),
   'ACCOMMODATION_VALUE' => array(
      'type' => 'currency',
      'label' => 'LBL_ACCOMMODATION_VALUE',
      'currency_format' => true,
      'width' => '10%',
      'default' => true,
   ),
   'ARCHIVAL' => array(
      'type' => 'bool',
      'label' => 'LBL_ARCHIVAL',
      'default' => true,
      'width' => '10%',
   ),
);
