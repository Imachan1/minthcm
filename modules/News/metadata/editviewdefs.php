<?php

$module_name = 'News';
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
         'includes' =>
         array(
            array(
               'file' => 'include/javascript/tiny_mce/tiny_mce.js',
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
               'news_type',
            ),
            array(
               'assigned_user_name',
               '',
            ),
            array(
               'content_of_announcement',
               '',
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
