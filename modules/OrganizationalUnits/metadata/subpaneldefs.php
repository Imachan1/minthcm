<?php

$layout_defs["OrganizationalUnits"]["subpanel_setup"] = array(
   'members' => array(
      'order' => 100,
      'module' => 'OrganizationalUnits',
      'subpanel_name' => 'default',
      'sort_order' => 'asc',
      'sort_by' => 'id',
      'title_key' => 'LBL_MEMBERS',
      'get_subpanel_data' => 'members',
      'add_subpanel_data' => 'member_id',
      'top_buttons' =>
      array(
         array(
            'widget_class' => 'SubPanelTopButtonQuickCreate',
         ),
         array(
            'widget_class' => 'SubPanelTopSelectButton',
            'mode' => 'MultiSelect',
         ),
      ),
   ),
   'positions_membership' => array(
      'order' => 200,
      'module' => 'Positions',
      'subpanel_name' => 'ForOrganizationalUnits',
      'sort_order' => 'asc',
      'sort_by' => 'id',
      'title_key' => 'LBL_POSITIONS_MEMBERSHIP',
      'get_subpanel_data' => 'positions_membership',
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
   'news' => array(
      'order' => 200,
      'module' => 'News',
      'subpanel_name' => 'default',
      'sort_order' => 'asc',
      'sort_by' => 'id',
      'title_key' => 'LBL_NEWS',
      'get_subpanel_data' => 'news',
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
   'employees' => array(
      'order' => 200,
      'module' => 'Employees',
      'subpanel_name' => 'default',
      'sort_order' => 'asc',
      'sort_by' => 'id',
      'title_key' => 'LBL_EMPLOYEES',
      'get_subpanel_data' => 'employees', 
      'top_buttons' => array(
         array(
            'widget_class' => 'SubPanelTopSelectButton',
            'mode' => 'MultiSelect',
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
