<?php

$module_name = 'Delegations';
$viewdefs [$module_name] = array(
   'DetailView' => array(
      'templateMeta' => array(
         'form' => array(
            'buttons' => array(
               'EDIT',
               'DUPLICATE',
               'DELETE',
            ),
            'links' => array(
               '<span id="pdf_generator"><span id="templateselect_span"></span><input title="PDF" onclick="openPDF(\'{$fields.id.value}\', \'Delegations\');" type="button" name="button" value="PDF"></span>',
            ),
         ),
         'includes' => array(
            array(
               'file' => 'modules/Delegations/js/delegations.js',
            ),
            array(
               'file' => 'include/SugarFields/Fields/Datetimecombo/Datetimecombo.js',
            ),
            array(
               'file' => 'modules/PDFTemplates/js/openpdf.js',
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
            'LBL_ADDITIONAL_INFORMATION' =>
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
         'javascript' => '<script> var currentModule="Delegations"</script>',
      ),
      'panels' => array(
         'default' => array(
            array(
               'name',
            ),
            array(
               'assigned_user_name',
               'owner',
            ),
            array(
               array(
                  'name' => 'purpose',
                  'label' => 'LBL_PURPOSE',
               ),
               array(
                  'name' => 'start_date',
                  'label' => 'LBL_START_DATE',
               ),
            ),
            array(
               'transport_cost_usdollar',
               array(
                  'name' => 'end_date',
                  'label' => 'LBL_END_DATE',
               ),
            ),
            array(
               'regiments_usdollar',
               'accommodation_lump_sum_usdollar',
            ),
            array(
               'total_accommodation_usdollar',
               'other_usdollar',
            ),
            array(
               'total_expenses_usdollar',
               'obtained_sum_usdollars',
            ),
            array(
               'return_sum_usdollar',
               'payoff_sum_usdollar',
            ),
            array(
               array(
                  'name' => 'delegation_locale_name',
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
         'LBL_ADDITIONAL_INFORMATION' => array(
            array(
               'regiments',
               'accommodation_lump_sum',
            ),
            array(
               'costs_sum',
               '',
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
