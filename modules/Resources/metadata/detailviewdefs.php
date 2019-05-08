<?php

$module_name = 'Resources';
$viewdefs[$module_name]['DetailView'] = array(
   'templateMeta' => array(
      'form' => array(
         'buttons' => array(
            'EDIT',
            'DUPLICATE',
            'DELETE',
            'FIND_DUPLICATES',
            array(
                  'customCode' => '{include file="modules/Resources/tpls/ShowReservationCalendarButton.tpl"}'
               ),
         )
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
            array(
               'name' => 'unavailable',
               'label' => 'LBL_UNAVAILABLE',
            ),
         ),
         array(
            array(
               'name' => 'type',
               'studio' => 'visible',
               'label' => 'LBL_TYPE',
            ),
         ),
         array(
            'employee_name',
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
