<?php

$subpanel_layout = array(
   'top_buttons' =>
   array(
      array(
         'widget_class' => 'SubPanelTopCreateButton',
      ),
      array(
         'widget_class' => 'SubPanelTopSelectButton',
         'popup_module' => 'WorkingMonths',
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
      'working_days' =>
      array(
         'type' => 'int',
         'vname' => 'LBL_WORKING_DAYS',
         'width' => '10%',
         'default' => true,
      ),
      'working_hours' =>
      array(
         'type' => 'int',
         'vname' => 'LBL_WORKING_HOURS',
         'width' => '10%',
         'default' => true,
      ),
      'edit_button' =>
      array(
         'vname' => 'LBL_EDIT_BUTTON',
         'widget_class' => 'SubPanelEditButton',
         'module' => 'WorkingMonths',
         'width' => '4%',
         'default' => true,
      ),
      'remove_button' =>
      array(
         'vname' => 'LBL_REMOVE',
         'widget_class' => 'SubPanelRemoveButton',
         'module' => 'WorkingMonths',
         'width' => '5%',
         'default' => true,
      ),
   ),
);
