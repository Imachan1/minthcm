<?php

$module_name = 'TermsOfEmployment';
$viewdefs [$module_name] = array(
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
      ),
      'panels' => array(
         'default' => array(
            array(
               'name',
               'contract_name',
            ),
            array(
               'term_starting_date',
               'term_ending_date',
            ),
            array(
               'date_of_signing',
               'assigned_user_name',
            ),
            array(
               'description',
            ),
         ),
         'LBL_PANEL_SALARY' => array(
            array(
               'gross',
               'net',
            ),
            array(
               'employer_cost',
               'currency_id',
            ),
         ),
      ),
   ),
);

