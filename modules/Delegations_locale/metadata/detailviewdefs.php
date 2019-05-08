<?php

$module_name = 'Delegations_locale';
$viewdefs [$module_name] = array(
   'DetailView' => array(
      'templateMeta' => array(
         'form' => array(
            'buttons' => array(
               'EDIT',
               'DUPLICATE',
               'DELETE',
               'FIND_DUPLICATES',
            ),
         ),
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
            array(
               array(
                  'name' => 'description',
                  'label' => 'LBL_DESCRIPTION',
               ),
            ),
         ),
         'LBL_PANEL_ASSIGNMENT' => array(
            array(
               array(
                  'name' => 'date_entered',
                  'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}'
               ),
               array(
                  'name' => 'date_modified',
                  'label' => 'LBL_DATE_MODIFIED',
                  'customCode' => '{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}'
               )
            )
         ),
      ),
   ),
);
