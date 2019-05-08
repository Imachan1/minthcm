<?php

$module_name = 'Certificates';
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
               array(
                  'name' => 'name',
                  'label' => 'LBL_NAME',
                  'displayParams' =>
                  array(
                     'required' => true,
                  ),
               ),
               'status'
            ),
            array(
               'start_date',
               'end_date',
            ),
            array(
               array(
                  'name' => 'candidate_name',
                  'label' => 'LBL_RELATIONSHIP_CANDIDATE_NAME',
               ),
               'employee_name',
            ),
            array(
               'description',
               '',
            ),
         ),
         'LBL_RECORDVIEW_PANEL' => array(
            array(
               'assigned_user_name',
               '',
            ),
         ),
      ),
   ),
);
