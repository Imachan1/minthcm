<?php

$module_name = 'Resources';
$viewdefs[$module_name]['QuickCreate'] = array(
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
   ),
   'panels' =>
   array(
      'default' =>
      array(
         array(
            'name',
            array(
               'name' => 'unavailable',
               'label' => 'LBL_UNAVAILABLE',
            ),
         ),
         array(
            array(
               'name' => 'type',
               'studio' => 'visible',
               'label' => 'LBL_TYPE',
            ),
         ),
         array(
            'employee_name',
            'assigned_user_name',
         ),
      ),
   ),
);
