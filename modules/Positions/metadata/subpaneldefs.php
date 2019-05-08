<?php

$layout_defs["Positions"]["subpanel_setup"] = array(
   'employees' => array(
      'order' => 50,
      'module' => 'Employees',
      'subpanel_name' => 'default',
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
   'recruitments' => array(
      'order' => 100,
      'module' => 'Recruitments',
      'subpanel_name' => 'default',
      'sort_order' => 'asc',
      'sort_by' => 'id',
      'title_key' => 'LBL_RECRUITMENTS_POSITIONS_FROM_RECRUITMENTS_TITLE',
      'get_subpanel_data' => 'recruitments',
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
   'organizationalunits_membership' => array(
      'order' => 100,
      'module' => 'OrganizationalUnits',
      'subpanel_name' => 'default',
      'sort_order' => 'asc',
      'sort_by' => 'id',
      'title_key' => 'LBL_ORGANIZATIONALUNITS_POSITIONS_MEMBERSHIP',
      'get_subpanel_data' => 'organizationalunits_membership',
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
   'onboardingtemplates' => array(
      'order' => 100,
      'module' => 'OnboardingTemplates',
      'subpanel_name' => 'default',
      'sort_order' => 'asc',
      'sort_by' => 'id',
      'title_key' => 'LBL_ONBOARDINGTEMPLATES_POSITIONS_FROM_ONBOARDINGTEMPLATES_TITLE',
      'get_subpanel_data' => 'onboardingtemplates',
      'top_buttons' =>
      array(
         array(
            'widget_class' => 'SubPanelTopButtonQuickCreate',
         ),
      ),
   ),
   'offboardingtemplates' => array(
      'order' => 100,
      'module' => 'OffboardingTemplates',
      'subpanel_name' => 'default',
      'sort_order' => 'asc',
      'sort_by' => 'id',
      'title_key' => 'LBL_OFFBOARDINGTEMPLATES_POSITIONS_FROM_OFFBOARDINGTEMPLATES_TITLE',
      'get_subpanel_data' => 'offboardingtemplates',
      'top_buttons' =>
      array(
         array(
            'widget_class' => 'SubPanelTopButtonQuickCreate',
         ),
      ),
   ),
   'positions_supervision_left' => array(
      'order' => 200,
      'module' => 'Positions',
      'subpanel_name' => 'for_Positions',
      'sort_order' => 'asc',
      'sort_by' => 'id',
      'title_key' => 'LBL_POSITIONS_POSITIONS_SUPERVISION_LEFT',
      'get_subpanel_data' => 'positions_supervision_left',
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
   'documents' => array(
      'order' => 300,
      'module' => 'Documents',
      'subpanel_name' => 'default',
      'sort_order' => 'asc',
      'sort_by' => 'id',
      'title_key' => 'LBL_DOCUMENTS',
      'get_subpanel_data' => 'documents',
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
   'benefits' => array(
      'order' => 400,
      'module' => 'Benefits',
      'subpanel_name' => 'default',
      'sort_order' => 'asc',
      'sort_by' => 'id',
      'title_key' => 'LBL_BENEFITS_SUBPANEL_FOR_POSITIONS',
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
      'order' => 400,
      'module' => 'Responsibilities',
      'subpanel_name' => 'default',
      'sort_order' => 'asc',
      'sort_by' => 'id',
      'title_key' => 'LBL_RESPONSIBILITIES_SUBPANEL_FOR_POSITIONS',
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
      'title_key' => 'LBL_COMPETENCYRATINGS_POSITIONS_TITLE',
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
      'title_key' => 'LBL_POSITIONS_APPRAISALS',
      'get_subpanel_data' => 'appraisals',
      'top_buttons' =>
      array(
         array(
            'widget_class' => 'SubPanelTopSelectButton',
            'mode' => 'MultiSelect',
         ),
      ),
   ),
   'careerpaths_from' => array(
      'order' => 100,
      'module' => 'CareerPaths',
      'subpanel_name' => 'default',
      'sort_order' => 'asc',
      'sort_by' => 'id',
      'title_key' => 'LBL_POSITIONS_CAREERPATHS_FROM_TITLE',
      'get_subpanel_data' => 'careerpaths_from',
      'top_buttons' =>
      array(
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
