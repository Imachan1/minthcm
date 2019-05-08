<?php

$module_name = 'Goals';
$subpanel_layout = array(
   'top_buttons' =>
   array(
      array(
         'widget_class' => 'SubPanelTopCreateButton',
      ),
      array(
         'widget_class' => 'SubPanelTopSelectButton',
         'popup_module' => 'Goals',
      ),
   ),
   'where' => '',
   'list_fields' =>
   array(
      'name' =>
      array(
         'vname' => 'LBL_NAME',
         'widget_class' => 'SubPanelDetailViewLink',
         'width' => '45%',
         'default' => true,
      ),
      'status' =>
      array(
         'type' => 'enum',
         'default' => true,
         'studio' => 'visible',
         'vname' => 'LBL_STATUS',
         'width' => '10%',
      ),
      'date_start' =>
      array(
         'type' => 'datetimecombo',
         'vname' => 'LBL_DATE_START',
         'width' => '10%',
         'default' => true,
      ),
      'date_end' =>
      array(
         'type' => 'datetimecombo',
         'vname' => 'LBL_DATE_END',
         'width' => '10%',
         'default' => true,
      ),
      'edit_button' =>
      array(
         'vname' => 'LBL_EDIT_BUTTON',
         'widget_class' => 'SubPanelEditButton',
         'module' => 'Goals',
         'width' => '4%',
         'default' => true,
      ),
      'remove_button' =>
      array(
         'vname' => 'LBL_REMOVE',
         'widget_class' => 'SubPanelRemoveButton',
         'module' => 'Goals',
         'width' => '5%',
         'default' => true,
      ),
   ),
);
