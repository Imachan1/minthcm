<?php

$viewdefs ['WorkingMonths'] = array(
   'EditView' =>
   array(
      'templateMeta' =>
      array(
         'maxColumns' => '2',
         'widths' =>
         array(
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
         'tabDefs' =>
         array(
            'DEFAULT' =>
            array(
               'newTab' => false,
               'panelDefault' => 'expanded',
            ),
         ),
         'includes' => array(
            array(
               'file' => 'modules/WorkingMonths/js/view.edit.js',
            ),
         ),
      ),
      'panels' =>
      array(
         'default' =>
         array(
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
      ),
   ),
);
