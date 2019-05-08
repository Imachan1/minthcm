<?php

$module_name = 'DashboardBackups';
$subpanel_layout = array(
   'top_buttons' => array(),
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
      'assigned_user_name' =>
      array(
         'link' => true,
         'type' => 'relate',
         'vname' => 'LBL_ASSIGNED_TO_NAME',
         'id' => 'ASSIGNED_USER_ID',
         'width' => '10%',
         'default' => true,
         'widget_class' => 'SubPanelDetailViewLink',
         'target_module' => 'Users',
         'target_record_key' => 'assigned_user_id',
      ),
      'dashboardhistory_name' =>
      array(
         'link' => true,
         'type' => 'relate',
         'vname' => 'LBL_DASHBOARDHISTORY_NAME',
         'id' => 'DASHBOARDHISTORY_ID',
         'width' => '10%',
         'default' => true,
         'widget_class' => 'SubPanelDetailViewLink',
         'target_module' => 'DashboardHistory',
         'target_record_key' => 'dashboardhistory_id',
      ),
      'date_entered' =>
      array(
         'type' => 'datetime',
         'vname' => 'LBL_DATE_ENTERED',
         'width' => '10%',
         'default' => true,
      ),
   ),
);
