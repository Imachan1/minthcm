<?php

$layout_defs["DashboardHistory"]["subpanel_setup"] = array(
   'dashboardbackups' => array(
      'order' => 1,
      'module' => 'DashboardBackups',
      'subpanel_name' => 'ForDashboardHistory',
      'sort_order' => 'desc',
      'sort_by' => 'date_entered',
      'title_key' => 'LBL_DASHBOARDBACKUPS',
      'get_subpanel_data' => 'dashboardbackups',
      'top_buttons' => array(),
   ),
);
