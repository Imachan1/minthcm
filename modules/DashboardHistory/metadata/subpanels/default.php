<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

$module_name = 'DashboardHistory';
$subpanel_layout = array(
   'top_buttons' => array(),
   'where' => '',
   'list_fields' => array(
      'name' => array(
         'vname' => 'LBL_NAME',
         'widget_class' => 'SubPanelDetailViewLink',
         'width' => '25%',
      ),
      'user_count' => array(
         'vname' => 'LBL_USER_COUNT',
         'width' => '10%',
      ),
      'assigned_user_name' => array(
         'name' => 'assigned_user_name',
         'vname' => 'LBL_LIST_ASSIGNED_TO_NAME',
         'widget_class' => 'SubPanelDetailViewLink',
         'target_record_key' => 'assigned_user_id',
         'target_module' => 'Users',
         'width' => '30%',
      ),
      'date_entered' => array(
         'vname' => 'LBL_SUBPANEL_DATE_ENTERED',
         'width' => '20%',
      ),
   ),
);
