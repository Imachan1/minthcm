<?php

$popupMeta = array(
   'moduleMain' => 'Delegations_locale',
   'varName' => 'Delegations_locale',
   'orderBy' => 'delegations_locale.name',
   'whereClauses' => array(
      'name' => 'delegations_locale.name',
      'regimen_value' => 'delegations_locale.regimen_value',
      'accommodation_value' => 'delegations_locale.accommodation_value',
   ),
   'whereStatement' => "delegations_locale.archival = '0'",
   'searchInputs' => array(
      'name',
      'regimen_value',
      'accommodation_value',
   ),
   'searchdefs' => array(
      'name' => array(
         'type' => 'name',
         'link' => true,
         'label' => 'LBL_NAME',
         'width' => '10%',
         'name' => 'name',
      ),
      'regimen_value' => array(
         'type' => 'currency',
         'label' => 'LBL_REGIMEN_VALUE',
         'currency_format' => true,
         'width' => '10%',
         'name' => 'regimen_value',
      ),
      'accommodation_value' => array(
         'type' => 'currency',
         'label' => 'LBL_ACCOMMODATION_VALUE',
         'currency_format' => true,
         'width' => '10%',
         'name' => 'accommodation_value',
      ),
   ),
   'listviewdefs' => array(
      'NAME' => array(
         'type' => 'name',
         'link' => true,
         'label' => 'LBL_NAME',
         'width' => '10%',
         'default' => true,
         'name' => 'name',
      ),
      'REGIMEN_VALUE' => array(
         'type' => 'currency',
         'label' => 'LBL_REGIMEN_VALUE',
         'currency_format' => true,
         'width' => '10%',
         'default' => true,
         'name' => 'regimen_value',
      ),
      'ACCOMMODATION_VALUE' => array(
         'type' => 'currency',
         'label' => 'LBL_ACCOMMODATION_VALUE',
         'currency_format' => true,
         'width' => '10%',
         'default' => true,
         'name' => 'accommodation_value',
      ),
      'CURRENCY_ID' => array(
         'type' => 'id',
         'studio' => 'visible',
         'label' => 'LBL_CURRENCY',
         'width' => '10%',
         'default' => false,
      ),
   ),
);
