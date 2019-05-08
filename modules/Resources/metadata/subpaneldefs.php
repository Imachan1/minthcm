<?php

$layout_defs['Resources'] = array(
   'subpanel_setup' => array(
      'meetings' => array(
         'top_buttons' => array(),
         'order' => 100,
         'sort_by' => 'name',
         'sort_order' => 'asc',
         'module' => 'Meetings',
         'refresh_page' => 1,
         'subpanel_name' => 'default',
         'get_subpanel_data' => 'meetings',
         'title_key' => 'LBL_MEETINGS',
      ),
      'calls' => array(
         'top_buttons' => array(),
         'order' => 100,
         'sort_by' => 'name',
         'sort_order' => 'asc',
         'module' => 'Calls',
         'refresh_page' => 1,
         'subpanel_name' => 'default',
         'get_subpanel_data' => 'calls',
         'title_key' => 'LBL_CALLS',
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
      'reservations' => array(
         'order' => 100,
         'module' => 'Reservations',
         'subpanel_name' => 'nonRemovable',
         'sort_order' => 'asc',
         'sort_by' => 'id',
         'title_key' => 'LBL_RESERVATIONS',
         'get_subpanel_data' => 'reservations',
         'top_buttons' => array(
            array(
               'widget_class' => 'SubPanelTopButtonQuickCreate_Reservations',
            ),
            array(
               'widget_class' => 'SubPanelTopSelectButton_Reservations',
               'mode' => 'MultiSelect',
            ),
         ),
      ),
   ),
);
