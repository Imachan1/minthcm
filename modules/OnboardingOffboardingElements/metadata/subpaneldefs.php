<?php

$layout_defs["OnboardingOffboardingElements"]["subpanel_setup"] = array(
   'onboardingtemplates' => array(
      'order' => 100,
      'module' => 'OnboardingTemplates',
      'subpanel_name' => 'for_elements',
      'sort_order' => 'asc',
      'sort_by' => 'id',
      'title_key' => 'LBL_ONBOARDINGTEMPLATES',
      'get_subpanel_data' => 'onboardingtemplates',
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
   'offboardingtemplates' => array(
      'order' => 100,
      'module' => 'OffboardingTemplates',
      'subpanel_name' => 'for_elements',
      'sort_order' => 'asc',
      'sort_by' => 'id',
      'title_key' => 'LBL_OFFBOARDINGTEMPLATES',
      'get_subpanel_data' => 'offboardingtemplates',
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
);
