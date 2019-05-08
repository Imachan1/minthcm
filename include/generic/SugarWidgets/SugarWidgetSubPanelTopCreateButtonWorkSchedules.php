<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

require_once 'include/generic/SugarWidgets/SugarWidgetSubPanelTopButton.php';

class SugarWidgetSubPanelTopCreateButtonWorkSchedules extends SugarWidgetSubPanelTopButton {

   public function __construct($layout_manager) {
      global $app_strings;

      parent::__construct($layout_manager);

      $this->module = 'SpentTime';
      $this->title = $app_strings['LBL_NEW_BUTTON_TITLE'];
      $this->access_key = $app_strings['LBL_NEW_BUTTON_KEY'];
      $this->form_value = translate('LBL_NEW_BUTTON_LABEL', $this->module);
      $this->acl = 'edit';
   }

   public function display($defines, $additionalFormFields = null, $nonbutton = false) {
      global $app_strings;

      $title = $app_strings['LBL_NEW_BUTTON_TITLE'];
      $value = $app_strings['LBL_NEW_BUTTON_LABEL'];
      $parent_bean = $defines['subpanel_definition']->parent_bean;

      $button = $this->_get_form($defines, $additionalFormFields);
      if ( $parent_bean->status != 'closed' ) {
         $button .= "<input title='$title' class='button' type='submit' name='{$this->getWidgetId()}' id='{$this->getWidgetId()}' value='$value'/>\n";
      }
      $button .= "</form>";

      return $button;
   }

}
