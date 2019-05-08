<?php

$module_name = 'DashboardBackups';
$viewdefs [$module_name] = array(
   'QuickCreate' =>
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
      ),
      'panels' =>
      array(
         'lbl_quickcreate_panel2' =>
         array(
            array(
               array(
                  'name' => 'date_entered',
                  'comment' => 'Date record created',
                  'label' => 'LBL_DATE_ENTERED',
               ),
               array(
                  'name' => 'date_modified',
                  'comment' => 'Date record last modified',
                  'label' => 'LBL_DATE_MODIFIED',
               ),
            ),
         ),
      ),
   ),
);
