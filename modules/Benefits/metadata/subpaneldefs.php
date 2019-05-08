<?php

$layout_defs['Benefits'] = array(
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
      'employees' => array(
         'order' => 100,
         'module' => 'Employees',
         'subpanel_name' => 'ForBenefits',
         'sort_order' => 'asc',
         'sort_by' => 'id',
         'title_key' => 'LBL_EMPLOYEES',
         'get_subpanel_data' => 'employees',
         'top_buttons' =>
         array(
            array(
               'widget_class' => 'SubPanelTopSelectButton',
               'mode' => 'MultiSelect',
            ),
         ),
      ),
      'positions' => array(
         'order' => 100,
         'module' => 'Positions',
         'subpanel_name' => 'default',
         'sort_order' => 'asc',
         'sort_by' => 'id',
         'title_key' => 'LBL_POSITIONS',
         'get_subpanel_data' => 'positions',
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
      'roles' => array(
         'order' => 100,
         'module' => 'EmployeeRoles',
         'subpanel_name' => 'ForBenefits',
         'sort_order' => 'asc',
         'sort_by' => 'id',
         'title_key' => 'LBL_ROLES',
         'get_subpanel_data' => 'roles',
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
   ),
);
