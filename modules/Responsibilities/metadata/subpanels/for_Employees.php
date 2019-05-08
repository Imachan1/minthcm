<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

$module_name = 'Responsibilities';
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
         'width' => '15%',
      ),
      'date_modified' => array(
         'vname' => 'LBL_DATE_MODIFIED',
         'width' => '15%',
      ),
      'assigned_user_name' =>
      array(
         'vname' => 'LBL_ASSIGNED_TO_NAME',
         'width' => '15%',
      ),
   ),
);
