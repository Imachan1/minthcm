<?php

$module_name = 'PeriodsOfEmployment';
$viewdefs[$module_name]['QuickCreate'] = array(
   'templateMeta' => array(
      'maxColumns' => '2',
      'widths' => array(
         array( 'label' => '10', 'field' => '30' ),
         array( 'label' => '10', 'field' => '30' )
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
   ),
   'panels' =>
   array(
      'default' =>
      array(
         0 =>
         array(
            0 => 'name',
            1 =>
            array(
               'name' => 'period_starting_date_c',
               'label' => 'LBL_PERIOD_STARTING_DATE',
            ),
         ),
         1 =>
         array(
            0 =>
            array(
               'employee_name',
               'comment' => 'Date record created',
               'label' => 'LBL_DATE_ENTERED',
            ),
            1 =>
            array(
               'name' => 'period_ending_date_c',
               'label' => 'LBL_PERIOD_ENDING_DATE',
            ),
         ),
         2 =>
         array(
            0 =>
            array(
               'name' => 'description',
               'comment' => 'Full text of the note',
               'label' => 'LBL_DESCRIPTION',
            ),
            1 => '',
         ),
      ),
   ),
);
