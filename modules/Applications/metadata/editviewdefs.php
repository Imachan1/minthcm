<?php

$module_name = 'Applications';
$viewdefs [$module_name] = array(
   'EditView' => array(
      'templateMeta' => array(
         'maxColumns' => '2',
         'useTabs' => false,
         'widths' => array(
            array(
               'label' => '10',
               'field' => '30',
            ),
            array(
               'label' => '10',
               'field' => '30',
            ),
         ),
         'tabDefs' => array(
            'LBL_DEFAULT' => array(
               'newTab' => false,
               'panelDefault' => 'expanded',
            ),
            'LBL_RECORDVIEW_PANEL' => array(
               'newTab' => false,
               'panelDefault' => 'expanded',
            ),
         ),
      ),
      'panels' => array(
         'lbl_default' => array(
            array(
               'name',
               'type'
            ),
            array(
               'status',
               'assigned_user_name',
            ),
            array(
               'description'
            ),
         ),
         'LBL_RECORDVIEW_PANEL' => array(
            array(
               array(
                  'name' => 'employee_name',
                  'label' => 'LBL_EMPLOYEE',
               ),
               '',
            ),
         ),
      ),
   ),
);
