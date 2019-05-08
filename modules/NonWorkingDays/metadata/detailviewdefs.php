<?php

$viewdefs['NonWorkingDays'] = array(
   'DetailView' => array(
      'templateMeta' => array(
         'form' => array(
            'buttons' => array(
               'EDIT',
               'DELETE',
            )
         ),
         'maxColumns' => '2',
         'widths' => array(
            array(
               'label' => '10',
               'field' => '30'
            ),
            array(
               'label' => '10',
               'field' => '30'
            )
         ),
         'useTabs' => true,
         'tabDefs' => array(
            'LBL_PANEL_INFORMATION' => array(
               'newTab' => true,
               'panelDefault' => 'expanded'
            ),
            'LBL_PANEL_OTHER' => array(
               'newTab' => true,
               'panelDefault' => 'expanded'
            )
         )
      ),
      'panels' => array(
         'LBL_PANEL_INFORMATION' => array(
            array(
               'name',
            ),
            array(
               'date',
               'week_day'
            ),
         ),
         'LBL_PANEL_OTHER' => array(
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
      )
   )
);
