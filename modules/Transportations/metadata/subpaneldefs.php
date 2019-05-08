<?php

$layout_defs["Transportations"]["subpanel_setup"] = array(
   'costs' => array(
      'order' => 100,
      'module' => 'Costs',
      'subpanel_name' => 'ForTransportations',
      'sort_order' => 'asc',
      'sort_by' => 'id',
      'title_key' => 'LBL_COSTS',
      'get_subpanel_data' => 'costs',
      'top_buttons' => array(
         array(
            'widget_class' => 'SubPanelTopButtonQuickCreateCosts',
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
