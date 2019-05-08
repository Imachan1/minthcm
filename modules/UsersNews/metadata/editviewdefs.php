<?php

$module_name = 'UsersNews';
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
               'news_name',
               'news_read',
            ),
            array(
               'not_display',
               'assigned_user_name',
            ),
         ),
      ),
   ),
);
;
?>
