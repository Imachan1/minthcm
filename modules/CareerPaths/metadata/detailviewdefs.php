<?php

$module_name = 'CareerPaths';
$viewdefs [$module_name] = array(
   'DetailView' =>
   array(
      'templateMeta' =>
      array(
         'form' =>
         array(
            'buttons' =>
            array(
               0 => 'EDIT',
               1 => 'DUPLICATE',
               2 => 'DELETE',
               3 => 'FIND_DUPLICATES',
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
         'useTabs' => true,
         'tabDefs' =>
         array(
            'DEFAULT' =>
            array(
               'newTab' => true,
               'panelDefault' => 'expanded',
            ),
            'LBL_PANEL_ASSIGNMENT' =>
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
               'position_from_name',
               'position_to_name',
            ),
            array(
               'description',
            ),
         ),
         'LBL_PANEL_ASSIGNMENT' =>
         array(
            array(
               'date_entered',
               'date_modified',
            ),
         ),
      ),
   ),
);
;
?>
