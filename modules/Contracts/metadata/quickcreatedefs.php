<?php

$module_name = 'Contracts';
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
         'tabDefs' =>
         array(
            'DEFAULT' =>
            array(
               'newTab' => true,
               'panelDefault' => 'expanded',
            ),
         ),
      ),
      'panels' =>
      array(
         'default' =>
         array(
            array(
               'name',
               'status',
            ),
            array(
               'contract_type',
               'date_of_signing',
            ),
            array(
               'daily_working_time',
               'employee_name',
            ),
            array(
               'periodofemployment_name',
               'assigned_user_name',
            ),
         ),
      ),
   ),
);
