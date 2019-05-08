<?php

$layout_defs["OffboardingTemplates"]["subpanel_setup"] = array(
   'offboardings' => array(
      'order' => 100,
      'module' => 'Offboardings',
      'subpanel_name' => 'default',
      'sort_order' => 'asc',
      'sort_by' => 'id',
      'title_key' => 'LBL_OFFBOARDINGS',
      'get_subpanel_data' => 'offboardings',
      'top_buttons' => array(),
   ),
   'elements' => array(
      'order' => 103,
      'module' => 'OnboardingOffboardingElements',
      'subpanel_name' => 'for_Templates',
      'sort_order' => 'asc',
      'sort_by' => 'days_from_start',
      'title_key' => 'LBL_ELEMENTS',
      'get_subpanel_data' => 'elements',
   ),
   'securitygroups' => array(
      'top_buttons' => array(array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'SecurityGroups', 'mode' => 'MultiSelect'),),
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
