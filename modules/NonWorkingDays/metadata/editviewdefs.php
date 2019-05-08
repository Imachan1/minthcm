<?php

$viewdefs ['NonWorkingDays'] = array(
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
            'LBL_PANEL_INFORMATION' =>
            array(
               'newTab' => false,
               'panelDefault' => 'expanded',
            ),
         ),
      ),
      'panels' => array(
         'LBL_PANEL_INFORMATION' => array(
            array(
               'date',
               '',
            ),
         ),
      )
   ),
);
