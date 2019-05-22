<?php

$module_name = 'Costs';
$viewdefs [$module_name] = array(
   'DetailView' => array(
      'templateMeta' => array(
         'form' => array(
            'buttons' => array(
               'EDIT',
               'DUPLICATE',
               'DELETE',
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
         'useTabs' => true,
         'tabDefs' =>
         array(
            'DEFAULT' =>
            array(
               'newTab' => true,
               'panelDefault' => 'expanded',
            ),
            'LBL_PANEL_ASSIGNMENT' =>
            array(
               'newTab' => true,
               'panelDefault' => 'expanded',
            ),
         ),
      ),
      'panels' => array(
         'default' => array(
            array(
               array(
                  'name' => 'type',
                  'studio' => 'visible',
                  'label' => 'LBL_TYPE',
               ),
               '',
            ),
            array(
               array(
                  'name' => 'type_of_meal',
               ),
               array(
                  'name' => 'accommodation_no',
               ),
            ),
            array(
               array(
                  'name' => 'transportation_name',
                  'label' => 'LBL_TRANSPORTATION_NAME',
               ),
               array(
                  'name' => 'delegation_name',
                  'label' => 'LBL_DELEGATION_NAME',
               ),
            ),
            array(
               array(
                  'name' => 'cost_amount',
                  'label' => 'LBL_COST_AMOUNT',
               ),
               array(
                  'name' => 'currency_id',
                  'studio' => 'visible',
                  'label' => 'LBL_CURRENCY',
               ),
            ),
            array(
               array(
                  'name' => 'cost_date',
                  'label' => 'LBL_COST_DATE',
               ),
               array(
                  'name' => 'cost_city',
                  'label' => 'LBL_COST_CITY',
               ),
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
