<?php

$module_name = 'PeriodsOfEmployment';
$viewdefs[$module_name]['DetailView'] = array(
   'templateMeta' => array(
      'form' => array(
         'buttons' => array(
            'EDIT',
            'DUPLICATE',
            'DELETE',
            'FIND_DUPLICATES',
         )
      ),
      'maxColumns' => '2',
      'widths' => array(
         array( 'label' => '10', 'field' => '30' ),
         array( 'label' => '10', 'field' => '30' )
      ),
   ),
   'panels' => array(
      array(
         'name',
         array(
            'name' => 'period_starting_date_c',
            'label' => 'LBL_PERIOD_STARTING_DATE',
         ),
      ),
      array(
         array(
            'name' => 'employee_name',
         ),
         array(
            'name' => 'period_ending_date_c',
            'label' => 'LBL_PERIOD_ENDING_DATE',
         ),
      ),
   )
);
