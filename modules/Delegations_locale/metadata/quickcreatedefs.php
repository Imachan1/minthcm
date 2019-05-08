<?php

$module_name = 'Delegations_locale';
$viewdefs [$module_name] = array(
   'QuickCreate' => array(
      'templateMeta' => array(
         'maxColumns' => '2',
         'widths' => array(
            array(
               'label' => '10',
               'field' => '30',
            ),
            array(
               'label' => '10',
               'field' => '30',
            ),
         ),
         'useTabs' => false,
      ),
      'panels' => array(
         'default' => array(
            array(
               'name',
               'archival'
            ),
            array(
               array(
                  'name' => 'regimen_value',
                  'label' => 'LBL_REGIMEN_VALUE',
               ),
               array(
                  'name' => 'currency_id',
                  'studio' => 'visible',
                  'label' => 'LBL_CURRENCY',
               ),
            ),
            array(
               array(
                  'name' => 'accommodation_value',
                  'label' => 'LBL_ACCOMMODATION_VALUE',
               ),
               '',
            ),
         ),
      ),
   ),
);
