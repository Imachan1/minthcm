<?php

$layout_defs["Recruitments"]["subpanel_setup"] = array(
   'candidatures' => array(
      'order' => 100,
      'module' => 'Candidatures',
      'subpanel_name' => 'originalCandidatures',
      'sort_order' => 'asc',
      'sort_by' => 'id',
      'title_key' => 'LBL_CANDIDATURES_RECRUITMENTS_FROM_CANDIDATURES_TITLE',
      'get_subpanel_data' => 'candidatures',
      'top_buttons' =>
      array(
         array(
            'widget_class' => 'SubPanelTopButtonQuickCreate',
         ),
      ),
   ),
   'candidatures_end' => array(
      'order' => 100,
      'module' => 'Candidatures',
      'subpanel_name' => 'currentCandidatures',
      'sort_order' => 'asc',
      'sort_by' => 'id',
      'title_key' => 'LBL_CANDIDATURES_RECRUITMENTS_END_FROM_CANDIDATURES_TITLE',
      'get_subpanel_data' => 'candidatures_end',
      'top_buttons' =>
      array(
         array(
            'widget_class' => 'SubPanelTopSelectButton',
            'mode' => 'MultiSelect',
         ),
         array(
            'widget_class' => 'SubPanelTopButtonQuickCreate',
         ),
      ),
   ),
   'activities' => array(
      'order' => 10,
      'sort_order' => 'desc',
      'sort_by' => 'date_start',
      'title_key' => 'LBL_ACTIVITIES_SUBPANEL_TITLE',
      'type' => 'collection',
      'subpanel_name' => 'activities',
      'module' => 'Activities',
      'top_buttons' => array(
         array(
            'widget_class' => 'SubPanelTopCreateTaskButton',
         ),
         array(
            'widget_class' => 'SubPanelTopScheduleMeetingButton',
         ),
         array(
            'widget_class' => 'SubPanelTopScheduleCallButton',
         ),
         array(
            'widget_class' => 'SubPanelTopComposeEmailButton',
         ),
      ),
      'collection_list' => array(
         'meetings' => array(
            'module' => 'Meetings',
            'subpanel_name' => 'ForActivities',
            'get_subpanel_data' => 'meetings',
         ),
         'tasks' => array(
            'module' => 'Tasks',
            'subpanel_name' => 'ForActivities',
            'get_subpanel_data' => 'tasks',
         ),
         'calls' => array(
            'module' => 'Calls',
            'subpanel_name' => 'ForActivities',
            'get_subpanel_data' => 'calls',
         ),
      ),
      'get_subpanel_data' => 'activities',
   ),
   "history" => array(
      'order' => 20,
      'sort_order' => 'desc',
      'sort_by' => 'date_modified',
      'title_key' => 'LBL_HISTORY',
      'type' => 'collection',
      'subpanel_name' => 'history',
      'module' => 'History',
      'top_buttons' =>
      array(
         0 =>
         array(
            'widget_class' => 'SubPanelTopCreateNoteButton',
         ),
         1 =>
         array(
            'widget_class' => 'SubPanelTopArchiveEmailButton',
         ),
         2 =>
         array(
            'widget_class' => 'SubPanelTopSummaryButton',
         ),
         3 =>
         array(
            'widget_class' => 'SubPanelTopFilterButton'
         ),
      ),
      'collection_list' =>
      array(
         'meetings' =>
         array(
            'module' => 'Meetings',
            'subpanel_name' => 'ForHistory',
            'get_subpanel_data' => 'meetings',
         ),
         'tasks' =>
         array(
            'module' => 'Tasks',
            'subpanel_name' => 'ForHistory',
            'get_subpanel_data' => 'tasks',
         ),
         'calls' =>
         array(
            'module' => 'Calls',
            'subpanel_name' => 'ForHistory',
            'get_subpanel_data' => 'calls',
         ),
         'notes' =>
         array(
            'module' => 'Notes',
            'subpanel_name' => 'ForHistory',
            'get_subpanel_data' => 'notes',
         ),
         'emails' =>
         array(
            'module' => 'Emails',
            'subpanel_name' => 'ForHistory',
            'get_subpanel_data' => 'emails',
         ),
      ),
      'get_subpanel_data' => 'history',
      'searchdefs' => array(
         'collection' =>
         array(
            'name' => 'collection',
            'label' => 'LBL_COLLECTION_TYPE',
            'type' => 'enum',
            'options' => $GLOBALS['app_list_strings']['collection_temp_list'],
            'default' => true,
            'width' => '10%',
         ),
         'name' =>
         array(
            'name' => 'name',
            'default' => true,
            'width' => '10%',
         ),
         'current_user_only' =>
         array(
            'name' => 'current_user_only',
            'label' => 'LBL_CURRENT_USER_FILTER',
            'type' => 'bool',
            'default' => true,
            'width' => '10%',
         ),
         'date_modified' =>
         array(
            'name' => 'date_modified',
            'default' => true,
            'width' => '10%',
         ),
      ),
   ),
   'securitygroups' => array(
      'top_buttons' => array( array( 'widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'SecurityGroups', 'mode' => 'MultiSelect' ), ),
      'order' => 900,
      'sort_by' => 'name',
      'sort_order' => 'asc',
      'module' => 'SecurityGroups',
      'refresh_page' => 1,
      'subpanel_name' => 'default',
      'get_subpanel_data' => 'SecurityGroups',
      'add_subpanel_data' => 'securitygroup_id',
      'title_key' => 'LBL_SECURITYGROUPS_SUBPANEL_TITLE',
   ),
);

$layout_defs['Recruitments']['subpanel_setup']['candidatures']['override_subpanel_name'] = 'Recruitments_subpanel_candidatures';
