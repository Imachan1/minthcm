<?php

$module_name = 'Delegations';
$viewdefs [$module_name] = array(
   'EditView' => array(
      'templateMeta' => array(
         'includes' => array(
            array(
               'file' => 'modules/Delegations/js/delegations.js',
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
               'assigned_user_name',
               'owner',
            ),
            array(
               array(
                  'name' => 'delegation_locale_name',
                  'displayParams' => array(
                     'required' => true,
                     'call_back_function' => 'popup_ret',
                     'field_to_name_array' => array(
                        'name' => 'delegation_locale_name',
                        'id' => 'delegation_locale_id',
                        'currency_id' => 'currency_id',
                     ),
                  ),
               ),
               array(
                  'name' => 'currency_id',
                  'studio' => 'visible',
                  'label' => 'LBL_CURRENCY',
               ),
            ),
            array(
               'exchange_rate',
               '',
            ),
            array(
               array(
                  'name' => 'start_date',
                  'label' => 'LBL_START_DATE',
               ),
               array(
                  'name' => 'end_date',
                  'label' => 'LBL_END_DATE',
               ),
            ),
            array(
               array(
                  'name' => 'purpose',
                  'label' => 'LBL_PURPOSE',
               ),
               array(
                  'name' => 'obtained_sum',
                  'label' => 'LBL_OBTAINED_SUM',
               ),
            ),
            array(
               'assured_number_of_breakfasts',
               'assured_number_of_dinners',
            ),
            array(
               'assured_number_of_suppers',
               'assured_number_of_accommodations',
            ),
            array(
               'description'
            ),
         ),
      ),
   ),
);
