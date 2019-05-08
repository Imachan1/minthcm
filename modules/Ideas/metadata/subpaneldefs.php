<?php

$layout_defs["Ideas"]["subpanel_setup"] = array(
   'notes' => array(
      'top_buttons' => array(
         array( 'widget_class' => 'SubPanelTopButtonQuickCreate'),
         array( 'widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'Notes', 'mode' => 'MultiSelect' ),
      ),
      'order' => 100,
      'sort_by' => 'name',
      'sort_order' => 'asc',
      'module' => 'Notes',
      'refresh_page' => 1,
      'subpanel_name' => 'default',
      'get_subpanel_data' => 'notes',
      'title_key' => 'LBL_NOTES',
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
