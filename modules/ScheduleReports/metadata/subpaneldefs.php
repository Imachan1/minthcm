<?php

$layout_defs["ScheduleReports"]["subpanel_setup"] = array(
   'users_schedulereports' => array(
      'order' => 1,
      'module' => 'Users',
      'subpanel_name' => 'default',
      'sort_order' => 'desc',
      'sort_by' => 'date_modified',
      'title_key' => 'LBL_USERS',
      'get_subpanel_data' => 'users',
      'top_buttons' => array(
         array(
            'widget_class' => 'SubPanelTopSelectButton',
            'mode' => 'MultiSelect',
         ),
      ),
   ),
   'schedulereports_schedulereportslogs' => array(
      'order' => 100,
      'module' => 'ScheduleReportsLogs',
      'subpanel_name' => 'default',
      'sort_order' => 'asc',
      'sort_by' => 'execute_data',
      'title_key' => 'LBL_SCHEDULEREPORTS_SCHEDULEREPORTSLOGS_FROM_SCHEDULEREPORTSLOGS_TITLE',
      'get_subpanel_data' => 'schedulereports_schedulereportslogs',
      'top_buttons' => array(),
   )
);
