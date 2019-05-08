<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

require_once 'include/generic/SugarWidgets/SugarWidgetSubPanelTopButtonQuickCreate.php';

class SugarWidgetSubPanelTopButtonQuickCreateCosts extends SugarWidgetSubPanelTopButtonQuickCreate {

   public function __construct(&$layout_manager) {
      global $app_strings;
      parent::SugarWidget($layout_manager);
      $this->module = 'Costs';
      $this->title = $app_strings['LBL_NEW_BUTTON_TITLE'];
      $this->access_key = $app_strings['LBL_NEW_BUTTON_KEY'];
      $this->form_value = translate('LBL_NEW_BUTTON_LABEL', $this->module);
      $this->acl = 'edit';
   }

   public function display($defines, $additionalFormFields = null, $nonbutton = false) {
      global $app_strings;
      $title = $app_strings['LBL_NEW_BUTTON_TITLE'];
      $value = $app_strings['LBL_NEW_BUTTON_LABEL'];
      $this->module = 'Costs';
      if ( ACLController::moduleSupportsACL($defines['module']) && !ACLController::checkAccess($defines['module'], 'edit', true) ) {
         $button = "<input title='$title'class='button' type='button' name='button' value='$value' disabled/>\n";
         return $button;
      }
      $additionalFormFields = array();
      if ( isset($defines['focus']->trans_date) ) {
         $additionalFormFields['cost_date'] = subStr($defines['focus']->trans_date, 0, 10);
      }
      if ( isset($defines['focus']->from_city) ) {
         $additionalFormFields['cost_city'] = $defines['focus']->from_city;
      }

      $button = $this->_get_form($defines, $additionalFormFields);
      $button .= "<input title='$title' class='button' type='submit' name='{$this->getWidgetId()}' id='{$this->getWidgetId()}' value='$value'/>\n";
      $button .= "</form>";
      return $button;
   }

}
