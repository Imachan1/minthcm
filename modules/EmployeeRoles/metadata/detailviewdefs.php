<?php

$module_name = 'EmployeeRoles';
$viewdefs[$module_name]['DetailView'] = array(
   'templateMeta' => array(
      'form' => array(
         'buttons' => array(
            'EDIT',
            'DUPLICATE',
            'DELETE',
            'FIND_DUPLICATES',
            array(
               'customCode' => true,
               'sugar_html' =>
               array(
                  'type' => 'button',
                  'value' => '{$MOD.LBL_CREATE_POSITION}',
                  'htmlOptions' =>
                  array(
                     'class' => 'button',
                     'name' => 'create_position_button',
                     'id' => 'create_position_button',
                     'title' => '{$MOD.LBL_CREATE_POSITION}',
                     'onClick' => 'createPosition.initialize()',
                  ),
                  'template' => '{if isset($positions_edit_access) && $positions_edit_access}[CONTENT]{/if}',
               ),
            ),
         )
      ),
      'includes' =>
      array(
         array(
            'file' => 'modules/EmployeeRoles/js/view.detail.js',
         ),
      ),
      'maxColumns' => '2',
      'widths' => array(
         array( 'label' => '10', 'field' => '30' ),
         array( 'label' => '10', 'field' => '30' )
      ),
      'useTabs' => true,
      'tabDefs' =>
      array(
         'DEFAULT' =>
         array(
            'newTab' => true,
            'panelDefault' => 'expanded',
         ),
         'LBL_EDITVIEW_PANEL1' =>
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
            'status',
         ),
         array(
            'description',
         ),
      ),
      'lbl_editview_panel1' =>
      array(
         array(
            'assigned_user_name',
            '',
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
);
