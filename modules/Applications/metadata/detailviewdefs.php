<?php

$module_name = 'Applications';
$viewdefs [$module_name] = array(
   'DetailView' => array(
      'templateMeta' => array(
         'form' => array(
            'buttons' => array(
               'EDIT',
               'DUPLICATE',
               'DELETE',
               'FIND_DUPLICATES',
            ),
         ),
         'useTabs' => true,
         'maxColumns' => '2',
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
               'newTab' => true,
               'panelDefault' => 'expanded',
            ),
            'LBL_RECORDVIEW_PANEL' => array(
               'newTab' => true,
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
               array(
                  'name' => 'assigned_user_name',
                  'label' => 'LBL_ASSIGNED_TO_NAME',
               ),
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
            ),
            array(
               array(
                  'name' => 'date_entered',
                  'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}',
                  'label' => 'LBL_DATE_ENTERED',
               ),
               array(
                  'name' => 'date_modified',
                  'customCode' => '{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}',
                  'label' => 'LBL_DATE_MODIFIED',
               ),
            ),
         ),
      ),
   ),
);
?>
