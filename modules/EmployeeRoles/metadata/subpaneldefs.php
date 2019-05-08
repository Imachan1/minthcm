<?php

$layout_defs['EmployeeRoles'] = array(
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
         'subpanel_name' => 'ForRoles',
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
      'benefits' => array(
         'order' => 100,
         'module' => 'Benefits',
         'subpanel_name' => 'default',
         'sort_order' => 'asc',
         'sort_by' => 'id',
         'title_key' => 'LBL_BENEFITS',
         'get_subpanel_data' => 'benefits',
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
      'responsibilities' => array(
         'order' => 100,
         'module' => 'Responsibilities',
         'subpanel_name' => 'default',
         'sort_order' => 'asc',
         'sort_by' => 'id',
         'title_key' => 'LBL_RESPONSIBILITIES',
         'get_subpanel_data' => 'responsibilities',
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
      'competencyratings' => array(
         'order' => 100,
         'module' => 'CompetencyRatings',
         'subpanel_name' => 'default',
         'sort_order' => 'asc',
         'sort_by' => 'id',
         'title_key' => 'LBL_COMPETENCYRATINGS',
         'get_subpanel_data' => 'competencyratings',
         'top_buttons' => array(
            array(
               'widget_class' => 'SubPanelTopButtonQuickCreate',
            ),
         ),
      ),
      'appraisals' => array(
         'order' => 100,
         'module' => 'Appraisals',
         'subpanel_name' => 'default',
         'sort_order' => 'asc',
         'sort_by' => 'id',
         'title_key' => 'LBL_APPRAISALS',
         'get_subpanel_data' => 'appraisals',
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
