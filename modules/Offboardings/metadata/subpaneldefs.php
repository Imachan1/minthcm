<?php

$layout_defs["Offboardings"]["subpanel_setup"] = array(
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
   'offboardings_trainings' => array(
      'order' => 100,
      'module' => 'Trainings',
      'subpanel_name' => 'default',
      'sort_order' => 'asc',
      'sort_by' => 'id',
      'title_key' => 'LBL_RELATIONSHIP_TRAININGS_NAME',
      'get_subpanel_data' => 'trainings',
      'top_buttons' => array(
         array(
            'widget_class' => 'SubPanelTopButtonQuickCreate',
         ),
         array(
            'widget_class' => 'SubPanelTopSelectButton',
            'mode' => 'MultiSelect',
         ),
      ),
   ),
   'tasks' => array(
      'order' => 100,
      'module' => 'Tasks',
      'subpanel_name' => 'default',
      'sort_order' => 'asc',
      'sort_by' => 'id',
      'title_key' => 'LBL_RELATIONSHIP_TASKS_NAME',
      'get_subpanel_data' => 'tasks',
      'top_buttons' => array(
         array(
            'widget_class' => 'SubPanelTopButtonQuickCreate',
         ),
         array(
            'widget_class' => 'SubPanelTopSelectButton',
            'mode' => 'MultiSelect',
         ),
      ),
   ),
);
