<?php

$layout_defs['Conclusions'] = array(
   'subpanel_setup' => array(
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
      'problems' => array(
         'order' => 100,
         'module' => 'Problems',
         'subpanel_name' => 'default',
         'sort_order' => 'asc',
         'sort_by' => 'id',
         'title_key' => 'LBL_PROBLEMS',
         'get_subpanel_data' => 'problems',
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
      'improvements' => array(
         'order' => 100,
         'module' => 'Improvements',
         'subpanel_name' => 'default',
         'sort_order' => 'asc',
         'sort_by' => 'id',
         'title_key' => 'LBL_IMPROVEMENTS',
         'get_subpanel_data' => 'improvements',
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
   ),
);
