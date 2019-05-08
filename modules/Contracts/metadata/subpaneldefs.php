<?php

$layout_defs["Contracts"]["subpanel_setup"] = array(
   'termsofemployment' => array(
      'top_buttons' => array(
         array(
            'widget_class' => 'SubPanelTopCreateButton'
         )
      ),
      'order' => 100,
      'sort_by' => 'term_starting_date',
      'sort_order' => 'asc',
      'module' => 'TermsOfEmployment',
      'refresh_page' => 1,
      'subpanel_name' => 'default',
      'get_subpanel_data' => 'termsofemployment',
      'title_key' => 'LBL_TERMSOFEMPLOYMENT',
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
