<?php

$module_name = 'Reservations';
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
         'includes' =>
         array(
            array(
               'file' => 'modules/Reservations/js/edit.sqs.js'
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
      ),
      'panels' =>
      array(
         'default' =>
         array(
            array(
               'name',
               array(
                  'name' => 'starting_date',
                  'label' => 'LBL_STARTING_DATE',
               ),
            ),
            array(
               array(
                  'name' => 'resource_name',
                  'label' => 'LBL_RESOURCES',
                  'displayParams' =>
                  array(
                     'initial_filter' => '&unavailable_advanced=0',
                  ),
               ),
               array(
                  'name' => 'ending_date',
                  'label' => 'LBL_ENDING_DATE',
               ),
            ),
            array(
               array(
                  'name' => 'delegation_name',
                  'label' => 'LBL_DELEGATIONS',
               ),
               'parent_name'
            ),
            array(
               'assigned_user_name',
               'employee_name',
            ),
         ),
      ),
   ),
);
?>
