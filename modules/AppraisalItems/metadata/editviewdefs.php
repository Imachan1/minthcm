<?php

$module_name = 'AppraisalItems';
$viewdefs [$module_name] = array(
   'EditView' =>
   array(
      'templateMeta' =>
      array(
         'includes' => array(
            array(
               'file' => 'modules/AppraisalItems/js/edit.js',
            ),
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
               'appraisal_name',
               'parent_name',
            ),
            array(
               'value',
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
