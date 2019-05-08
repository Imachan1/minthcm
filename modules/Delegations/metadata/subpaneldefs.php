<?php

$layout_defs["Delegations"]["subpanel_setup"] = array(
   'costs' => array(
      'order' => 100,
      'module' => 'Costs',
      'subpanel_name' => 'ForDelegations',
      'sort_order' => 'asc',
      'sort_by' => 'cost_date',
      'title_key' => 'LBL_COSTS',
      'get_subpanel_data' => 'costs',
      'top_buttons' => array(
         array(
            'widget_class' => 'SubPanelTopButtonQuickCreate',
         ),
      ),
   ),
   'transportations' => array(
      'order' => 100,
      'module' => 'Transportations',
      'subpanel_name' => 'ForDelegations',
      'sort_order' => 'asc',
      'sort_by' => 'trans_date',
      'title_key' => 'LBL_TRANSPORTATIONS',
      'get_subpanel_data' => 'transportations',
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
   'workschedules' => array(
      'order' => 100,
      'module' => 'WorkSchedules',
      'subpanel_name' => 'default',
      'sort_order' => 'asc',
      'sort_by' => 'id',
      'title_key' => 'LBL_WORKSCHEDULES',
      'get_subpanel_data' => 'workschedules',
      'top_buttons' => array(
         array(
            'widget_class' => 'SubPanelTopSelectButton_Delegations',
            'mode' => 'MultiSelect',
         ),
      ),
   ),
   'reservations' => array(
      'order' => 100,
      'module' => 'Reservations',
      'subpanel_name' => 'default',
      'sort_order' => 'asc',
      'sort_by' => 'id',
      'title_key' => 'LBL_RESERVATIONS',
      'get_subpanel_data' => 'reservations',
      'top_buttons' =>
      array(
         0 =>
         array(
            'widget_class' => 'SubPanelTopButtonQuickCreate_Reservations',
         ),
         1 =>
         array(
            'widget_class' => 'SubPanelTopSelectButton',
            'mode' => 'MultiSelect',
         ),
      ),
   )
);
