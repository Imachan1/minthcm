<?php

$layout_defs['Employees'] = array(
   'subpanel_setup' => array(
      "spenttime" => array(
         'order' => 100,
         'module' => 'SpentTime',
         'subpanel_name' => 'default',
         'sort_order' => 'asc',
         'sort_by' => 'id',
         'title_key' => 'LBL_USERS_SPENT_TIME_TITLE',
         'get_subpanel_data' => 'spenttime',
         'top_buttons' => array(),
      ),
      "contracts" => array(
         'order' => 100,
         'module' => 'Contracts',
         'subpanel_name' => 'default',
         'sort_order' => 'asc',
         'sort_by' => 'id',
         'title_key' => 'LBL_CONTRACTS',
         'get_subpanel_data' => 'contracts',
         'top_buttons' => array(
            array(
               'widget_class' => 'SubPanelTopButtonQuickCreate',
            ),
         ),
      ),
      "certificates" => array(
         'order' => 100,
         'module' => 'Certificates',
         'subpanel_name' => 'default',
         'sort_order' => 'asc',
         'sort_by' => 'id',
         'title_key' => 'LBL_CERTIFICATES',
         'get_subpanel_data' => 'certificates',
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
      "reservations" => array(
         'order' => 100,
         'module' => 'Reservations',
         'subpanel_name' => 'nonRemovable',
         'sort_order' => 'asc',
         'sort_by' => 'id',
         'title_key' => 'LBL_RESERVATIONS',
         'get_subpanel_data' => 'reservations',
         'top_buttons' => array(
            array(
               'widget_class' => 'SubPanelTopButtonQuickCreate',
            ),
         ),
      ),
      "periodsofemployment" => array(
         'order' => 100,
         'module' => 'PeriodsOfEmployment',
         'subpanel_name' => 'default',
         'sort_order' => 'asc',
         'sort_by' => 'id',
         'title_key' => 'LBL_PERIODSOFEMPLOYMENT',
         'get_subpanel_data' => 'periodsofemployment',
         'top_buttons' => array(),
      ),
      "goals" => array(
         'order' => 100,
         'module' => 'Goals',
         'subpanel_name' => 'default',
         'sort_order' => 'asc',
         'sort_by' => 'id',
         'title_key' => 'LBL_GOALS',
         'get_subpanel_data' => 'goals',
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
      "appraisals" => array(
         'order' => 100,
         'module' => 'Appraisals',
         'subpanel_name' => 'noCreateNoRemove',
         'sort_order' => 'asc',
         'sort_by' => 'id',
         'title_key' => 'LBL_APPRAISALS',
         'get_subpanel_data' => 'appraisals',
         'top_buttons' => array(
            array(
               'widget_class' => 'SubPanelTopButtonQuickCreate',
            ),
         ),
      ),
      'roles' => array(
         'order' => 100,
         'module' => 'EmployeeRoles',
         'subpanel_name' => 'default',
         'sort_order' => 'asc',
         'sort_by' => 'id',
         'title_key' => 'LBL_ROLES',
         'get_subpanel_data' => 'roles',
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
      'benefits' => array(
         'order' => 100,
         'module' => 'Benefits',
         'subpanel_name' => 'default',
         'sort_order' => 'asc',
         'sort_by' => 'id',
         'title_key' => 'LBL_BENEFITS',
         'get_subpanel_data' => 'benefits',
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
      'responsibilities_employee' => array(
         'order' => 100,
         'module' => 'Responsibilities',
         'subpanel_name' => 'for_Employees',
         'sort_order' => 'asc',
         'sort_by' => 'id',
         'title_key' => 'LBL_RESPONSIBILITIES',
         'get_subpanel_data' => 'function:fetchAllResponsibilities',
         'top_buttons' => array(),
      ),
      "onboardings" => array(
         'order' => 100,
         'module' => 'Onboardings',
         'subpanel_name' => 'default',
         'sort_order' => 'asc',
         'sort_by' => 'id',
         'title_key' => 'LBL_ONBOARDINGS',
         'get_subpanel_data' => 'onboardings',
         'top_buttons' => array(),
      ),
      "offboardings" => array(
         'order' => 100,
         'module' => 'Offboardings',
         'subpanel_name' => 'default',
         'sort_order' => 'asc',
         'sort_by' => 'id',
         'title_key' => 'LBL_OFFBOARDINGS',
         'get_subpanel_data' => 'offboardings',
         'top_buttons' => array(),
      ),
      "competencyratings" => array(
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
      'organizationalunits' => array(
         'order' => 100,
         'module' => 'OrganizationalUnits',
         'subpanel_name' => 'default',
         'sort_order' => 'asc',
         'sort_by' => 'id',
         'title_key' => 'LBL_RELATIONSHIP_ORGANIZATIONALUNITS_NAME',
         'get_subpanel_data' => 'organizationalunits',
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
      'applications' => array(
         'order' => 100,
         'module' => 'Applications',
         'subpanel_name' => 'default',
         'sort_order' => 'asc',
         'sort_by' => 'id',
         'title_key' => 'LBL_APPLICATIONS_SUBPANEL',
         'get_subpanel_data' => 'applications',
         'top_buttons' => array(
            array(
               'widget_class' => 'SubPanelTopButtonQuickCreate',
            ),
         ),
      ),
   ));
