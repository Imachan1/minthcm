<?php

$module_name = 'DashboardManager';
$viewdefs [$module_name] = array(
   'EditView' =>
   array(
      'templateMeta' =>
      array(
         'includes' => array(
         ),
         'maxColumns' => '2',
         'form' =>
         array(
            'hidden' =>
            array(
            ),
         ),
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
         'default' =>
         array(
            array(
               'name',
               '',
            ),
            array(
               'assigned_user_name',
               '',
            ),
            array(
               'description',
            ),
         ),
      ),
   ),
);
