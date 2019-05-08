<?php

$module_name = 'CareerPaths';
$subpanel_layout = array(
   'top_buttons' =>
   array(
      array(
         'widget_class' => 'SubPanelTopCreateButton',
      ),
      array(
         'widget_class' => 'SubPanelTopSelectButton',
         'popup_module' => 'CareerPaths',
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
      'date_modified' =>
      array(
         'type' => 'datetime',
         'vname' => 'LBL_DATE_MODIFIED',
         'width' => '10%',
         'default' => true,
      ),
      'date_entered' =>
      array(
         'type' => 'datetime',
         'vname' => 'LBL_DATE_ENTERED',
         'width' => '10%',
         'default' => true,
      ),
      'edit_button' =>
      array(
         'vname' => 'LBL_EDIT_BUTTON',
         'widget_class' => 'SubPanelEditButton',
         'module' => 'CareerPaths',
         'width' => '4%',
         'default' => true,
      ),
   ),
);
