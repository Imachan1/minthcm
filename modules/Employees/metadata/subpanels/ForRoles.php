<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

$module_name = 'Employee';

$subpanel_layout = array(
   'top_buttons' => array(
      array( 'widget_class' => 'SubPanelTopCreateButton' ),
      array( 'widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'Employees' ),
   ),
   'where' => '',
   'list_fields' => array(
      'first_name' => array(
         'usage' => 'query_only',
      ),
      'last_name' => array(
         'usage' => 'query_only',
      ),
      'full_name' => array(
         'vname' => 'LBL_LIST_NAME',
         'widget_class' => 'SubPanelDetailViewLink',
         'module' => 'Users',
         'width' => '25%',
      ),
      'organizationalunit_name' => array(
         'width' => '10%',
         'vname' => 'LBL_ORGANIZATIONALUNIT_NAME',
         'widget_class' => 'SubPanelDetailViewLink',
      ),
      'POSITION_NAME' => array(
         'width' => '15%',
         'vname' => 'LBL_POSITION_NAME',
      ),
      'REPORTS_TO_NAME' => array(
         'width' => '15%',
         'vname' => 'LBL_LIST_REPORTS_TO_NAME',
      ),
      'email1' => array(
         'vname' => 'LBL_LIST_EMAIL',
         'width' => '25%',
      ),
      'EMPLOYEE_STATUS' => array(
         'width' => '10%',
         'vname' => 'LBL_LIST_EMPLOYEE_STATUS',
      ),
      'remove_button' => array(
         'vname' => 'LBL_REMOVE',
         'widget_class' => 'SubPanelRemoveButton',
         'module' => 'Users',
         'width' => '4%',
         'linked_field' => 'users',
      ),
   ),
);
