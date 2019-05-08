<?php

require_once('include/generic/SugarWidgets/SugarWidgetSubPanelTopSelectButton.php');

class SugarWidgetSubPanelTopSelectButton_Delegations extends SugarWidgetSubPanelTopSelectButton {

   public function display($widget_data, $additionalFormFields = null, $nonbutton = false) {
      $initial_filter_fields = [
         'workschedules_type_advanced' => 'workschedules_type_advanced',
      ];
      $widget_data['focus']->workschedules_type_advanced = true;
      if ( empty($widget_data['initial_filter_fields']) ) {
         $widget_data['initial_filter_fields'] = $initial_filter_fields;
      } else {
         $widget_data['initial_filter_fields'] = array_merge($widget_data['initial_filter_fields'], $initial_filter_fields);
      }
      return parent::display($widget_data, $additionalFormFields, $nonbutton);
   }

}
