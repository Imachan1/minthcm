<?php

$module_name = 'Reservations';
$viewdefs [$module_name] = array(
   'DetailView' =>
   array(
      'templateMeta' =>
      array(
         'form' =>
         array(
            'buttons' =>
            array(
               'EDIT',
               'DUPLICATE',
               'DELETE',
               'FIND_DUPLICATES',
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
            'LBL_DETAILVIEW_PANEL1' =>
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
               'name',
               array(
                  'name' => 'starting_date',
                  'label' => 'LBL_STARTING_DATE',
               ),
            ),
            array(
               array(
                  'name' => 'resource_name',
                  'label' => 'LBL_RESOURCES',
               ),
               array(
                  'name' => 'ending_date',
                  'label' => 'LBL_ENDING_DATE',
               ),
            ),
            array(
               array(
                  'name' => 'delegation_name',
                  'label' => 'LBL_DELEGATIONS',
               ),
               'parent_name'
            ),
            array(
               'description',
            ),
         ),
         'lbl_detailview_panel1' =>
         array(
            array('assigned_user_name', 'employee_name'),
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
