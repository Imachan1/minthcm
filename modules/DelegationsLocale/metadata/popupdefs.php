<?php

$popupMeta = array(
   'moduleMain' => 'DelegationsLocale',
   'varName' => 'DelegationsLocale',
   'orderBy' => 'delegationslocale.name',
   'whereClauses' => array(
      'name' => 'delegationslocale.name',
      'regimen_value' => 'delegationslocale.regimen_value',
      'accommodation_value' => 'delegationslocale.accommodation_value',
   ),
   'whereStatement' => "delegationslocale.archival = '0'",
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
