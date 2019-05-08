<?php

$module_name = 'Goals';
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
               'status',
            ),
            array(
               'date_start',
               'date_end',
            ),
            array(
               'assigned_user_name',
               'employee_name',
            ),
            array(
               'description',
            ),
         ),
      ),
   ),
);
;
?>
