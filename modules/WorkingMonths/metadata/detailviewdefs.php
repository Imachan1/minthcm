<?php

$viewdefs ['WorkingMonths'] = array(
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
         'tabDefs' => array(
            'LBL_PANEL_BASIC' => array(
               'newTab' => true,
               'panelDefault' => 'expanded',
            ),
            'LBL_PANEL_OTHER' => array(
               'newTab' => true,
               'panelDefault' => 'expanded',
            ),
         ),
      ),
      'panels' => array(
         'LBL_PANEL_BASIC' => array(
            array(
               'name',
            ),
            array(
               array(
                  'name' => 'year',
                  'label' => 'LBL_YEAR',
               ),
               array(
                  'name' => 'months',
                  'studio' => 'visible',
                  'label' => 'LBL_MONTHS',
               ),
            ),
            array(
               array(
                  'name' => 'working_days',
                  'label' => 'LBL_WORKING_DAYS',
               ),
               array(
                  'name' => 'working_hours',
                  'label' => 'LBL_WORKING_HOURS',
               ),
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
      ),
   ),
);
