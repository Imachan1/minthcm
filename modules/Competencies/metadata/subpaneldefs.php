<?php

$layout_defs["Competencies"]["subpanel_setup"] = array(
   'competencyratings' => array(
      'order' => 100,
      'module' => 'CompetencyRatings',
      'subpanel_name' => 'default',
      'sort_order' => 'asc',
      'sort_by' => 'id',
      'title_key' => 'LBL_COMPETENCYRATINGS',
      'get_subpanel_data' => 'competencyratings',
      'top_buttons' => array( array( 'widget_class' => 'SubPanelTopCreateButton' ) ),
   ),
   'appraisalitems' => array(
      'order' => 200,
      'module' => 'AppraisalItems', 
      'subpanel_name' => 'default',
      'sort_order' => 'asc',
      'sort_by' => 'id',
      'title_key' => 'LBL_APPRAISALITEMS',
      'get_subpanel_data' => 'appraisalitems',
      'top_buttons' => array(
         array(
            'widget_class' => 'SubPanelTopButtonQuickCreate',
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
