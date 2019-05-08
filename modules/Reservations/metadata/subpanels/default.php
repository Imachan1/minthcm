<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

$module_name = 'Reservations';
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
         'width' => '10%',
      ),
      'date_modified' => array(
         'vname' => 'LBL_DATE_MODIFIED',
         'width' => '10%',
      ),
      'resource_name' => array(
         'vname' => 'LBL_RESOURCES',
         'width' => '10%',
      ),
      'delegation_name' => array(
         'vname' => 'LBL_DELEGATIONS',
         'width' => '10%',
      ),
      'parent_name' => array(
         'vname' => 'LBL_PARENT_NAME',
         'widget_class' => 'SubPanelDetailViewLink',
         'width' => '20%',
         'sortable' => false,
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
      'parent_id' => array(
         'usage' => 'query_only',
      ),
      'parent_type' => array(
         'usage' => 'query_only',
      ),
   ),
);
