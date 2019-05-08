<?php

$module_name = 'Costs';
$viewdefs [$module_name] = array(
   'QuickCreate' => array(
      'templateMeta' => array(
         'includes' => array(
            array(
               'file' => 'modules/Costs/js/costs.js',
            ),
         ),
         'form' => array(
            'hidden' => array(
               '<input type="hidden" name="delegation_name" value="{$fields.delegation_name.value}">',
               '<input type="hidden" name="delegation_id" value="{$fields.delegation_id.value}">',
               '<input type="hidden" name="transportation_name" value="{$fields.transportation_name.value}">',
               '<input type="hidden" name="transportation_id" value="{$fields.transportation_id.value}">',
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
               array(
                  'name' => 'type',
                  'studio' => 'visible',
                  'label' => 'LBL_TYPE',
               ),
               array(
                  'name' => 'accommodation_no',
               ), 
               ),
            array(
               array(
                  'name' => 'type_of_meal',
               ),
               '',
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
      ),
   ),
);
