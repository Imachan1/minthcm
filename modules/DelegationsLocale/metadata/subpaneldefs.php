<?php

// created: 2012-04-10 18:13:39
$layout_defs["DelegationsLocale"]["subpanel_setup"] = array(
   'delegations' => array(
      'order' => 100,
      'module' => 'Delegations',
      'subpanel_name' => 'default_no_delete',
      'sort_order' => 'asc',
      'sort_by' => 'id',
      'title_key' => 'LBL_DELEGATIONS',
      'get_subpanel_data' => 'delegations',
      'top_buttons' => array(),
   ),
   'securitygroups' => array(
      'top_buttons' => array(
         array(
            'widget_class' => 'SubPanelTopSelectButton',
            'popup_module' => 'SecurityGroups',
            'mode' => 'MultiSelect'
         ),
      ),
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
