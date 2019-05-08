<?php

require_once('include/generic/SugarWidgets/SugarWidgetSubPanelTopSelectButton.php');

class SugarWidgetSubPanelTopSelectButton_Reservations extends SugarWidgetSubPanelTopSelectButton {

   public function __construct(&$layout_manager) {
      parent::__construct($layout_manager);
      global $app_strings;
      $this->module = 'Reservations';
      $this->title = $app_strings['LBL_SELECT_BUTTON_LABEL'];
      $this->access_key = $app_strings['LBL_SELECT_BUTTON_LABEL'];
      $this->form_value = translate('LBL_SELECT_BUTTON_LABEL', $this->module);
      $this->acl = 'edit';
   }

   public function display($widget_data, $additionalFormFields = null, $nonbutton = false) {

      $parent_bean = $widget_data['subpanel_definition']->parent_bean;
      $module_name = $parent_bean->module_name;

      if ( $module_name == 'Resources' ) {
         $button = false;
      } else {
         $button = parent::display($widget_data, $additionalFormFields, $nonbutton);
      }

      return $button;
   }

}
