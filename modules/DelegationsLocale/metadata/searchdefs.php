<?php

$module_name = 'DelegationsLocale';
$searchdefs [$module_name] = array(
   'layout' => array(
      'basic_search' => array(
         'name' => array(
            'name' => 'name',
            'default' => true,
            'width' => '10%',
         ),
      ),
      'advanced_search' => array(
         'name' => array(
            'name' => 'name',
            'default' => true,
            'width' => '10%',
         ),
         'regimen_value' => array(
            'type' => 'currency',
            'label' => 'LBL_REGIMEN_VALUE',
            'currency_format' => true,
            'width' => '10%',
            'default' => true,
            'name' => 'regimen_value',
         ),
         'accommodation_value' => array(
            'type' => 'currency',
            'label' => 'LBL_ACCOMMODATION_VALUE',
            'currency_format' => true,
            'width' => '10%',
            'default' => true,
            'name' => 'accommodation_value',
         ),
         'archival' => array(
            'name' => 'archival',
            'type' => 'bool',
            'label' => 'LBL_ARCHIVAL',
            'default' => true,
            'width' => '10%',
         ),
      ),
   ),
   'templateMeta' => array(
      'maxColumns' => '3',
      'maxColumnsBasic' => '4',
      'widths' => array(
         'label' => '10',
         'field' => '30',
      ),
   ),
);
