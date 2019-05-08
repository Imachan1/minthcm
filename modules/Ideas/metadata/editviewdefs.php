<?php

$module_name = 'Ideas';
$viewdefs [$module_name] = array(
   'EditView' =>
   array(
      'templateMeta' =>
      array(
         'includes' => array(
            array( 'file' => 'modules/Ideas/js/edit.js' ),
         ),
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
      'panels' =>
      array(
         'default' =>
         array(
            array(
               'name',
               'status',
            ),
            array(
               'description',
            ),
            array(
               'explanation',
            ),
            array(
               'assigned_user_name',
               'user_name',
            ),
         ),
      ),
   ),
);
;
?>
