<?php

$dashletData['DelegationsLocaleDashlet']['searchFields'] = array(
   'name' => array(
      'default' => '',
   ),
);
$dashletData['DelegationsLocaleDashlet']['columns'] = array(
   'name' => array(
      'width' => '40%',
      'label' => 'LBL_LIST_NAME',
      'link' => true,
      'default' => true,
      'name' => 'name',
   ),
   'regimen_value' => array(
      'type' => 'currency',
      'label' => 'LBL_REGIMEN_VALUE',
      'currency_format' => true,
      'width' => '10%',
      'default' => true,
   ),
   'accommodation_value' => array(
      'type' => 'currency',
      'label' => 'LBL_ACCOMMODATION_VALUE',
      'currency_format' => true,
      'width' => '10%',
      'default' => true,
   ),
   'archival' => array(
      'name' => 'archival',
      'type' => 'bool',
      'label' => 'LBL_ARCHIVAL',
      'default' => true,
      'width' => '10%',
   ),
);
