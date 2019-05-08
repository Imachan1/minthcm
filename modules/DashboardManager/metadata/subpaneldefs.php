<?php

$layout_defs["DashboardManager"]["subpanel_setup"] = array(
   'users_locked_dashboards' => array(
      'order' => 1,
      'module' => 'Users',
      'subpanel_name' => 'default',
      'sort_order' => 'asc',
      'sort_by' => 'id',
      'title_key' => 'LBL_USERS_LOCKED_DASHBOARDS',
      'get_subpanel_data' => 'users_locked_dashboards',
      'top_buttons' =>
      array(
         array(
            'widget_class' => 'SubPanelTopSelectButtonForDM',
            'mode' => 'MultiSelect',
         ),
      ),
   ),
   'users_forced_tabs_dashboards' => array(
      'order' => 2,
      'module' => 'Users',
      'subpanel_name' => 'default',
      'sort_order' => 'asc',
      'sort_by' => 'id',
      'title_key' => 'LBL_USERS_FORCED_TABS_DASHBOARDS',
      'get_subpanel_data' => 'users_forced_tabs_dashboards',
      'top_buttons' =>
      array(
         array(
            'widget_class' => 'SubPanelTopSelectButtonForDM',
            'mode' => 'MultiSelect',
         ),
      ),
   ),
   'users_one_time_default_dashboards' => array(
      'order' => 3,
      'module' => 'Users',
      'subpanel_name' => 'default',
      'sort_order' => 'asc',
      'sort_by' => 'id',
      'title_key' => 'LBL_USERS_ONE_TIME_DEFAULT_DASHBOARDS',
      'get_subpanel_data' => 'users_one_time_default_dashboards',
      'top_buttons' =>
      array(
         array(
            'widget_class' => 'SubPanelTopSelectButtonForDM',
            'mode' => 'MultiSelect',
         ),
      ),
   ),
   'dashboardhistory' => array(
      'order' => 4,
      'module' => 'DashboardHistory',
      'subpanel_name' => 'default',
      'sort_order' => 'desc',
      'sort_by' => 'date_entered',
      'title_key' => 'LBL_DASHBOARDHISTORY',
      'get_subpanel_data' => 'dashboardhistory',
      'top_buttons' => array(
      ),
   ),
   'dashboardbackups' => array(
      'order' => 5,
      'module' => 'DashboardBackups',
      'subpanel_name' => 'default',
      'sort_order' => 'desc',
      'sort_by' => 'date_entered',
      'title_key' => 'LBL_DASHBOARDBACKUPS',
      'get_subpanel_data' => 'dashboardbackups',
      'top_buttons' => array(
      ),
   ),
);
