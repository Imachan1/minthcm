<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

$module_name = 'PeriodsOfEmployment';
$subpanel_layout = array(
   'top_buttons' => array(
      array( 'widget_class' => 'SubPanelTopCreateButton' ),
      array( 'widget_class' => 'SubPanelTopSelectButton', 'popup_module' => $module_name ),
   ),
   'where' => '',
   'list_fields' => array(
      'name' => array(
         'vname' => 'LBL_NAME',
         'widget_class' => 'SubPanelDetailViewLink',
         'width' => '45%',
      ),
      'period_starting_date' =>
      array(
         'type' => 'date',
         'vname' => 'LBL_PERIOD_STARTING_DATE',
         'width' => '10%',
      ),
      'period_ending_date' =>
      array(
         'type' => 'date',
         'vname' => 'LBL_PERIOD_ENDING_DATE',
         'width' => '10%',
      ),
      'employee_name' => array(
         'vname' => 'LBL_EMPLOYEE',
         'width' => '45%',
      ),
      'edit_button' => array(
         'vname' => 'LBL_EDIT_BUTTON',
         'widget_class' => 'SubPanelEditButton',
         'module' => $module_name,
         'width' => '4%',
      ),
      'remove_button' => array(
         'vname' => 'LBL_REMOVE',
         'widget_class' => 'SubPanelRemoveButton',
         'module' => $module_name,
         'width' => '5%',
      ),
   ),
);
