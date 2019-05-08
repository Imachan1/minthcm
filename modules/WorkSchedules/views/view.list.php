<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

require_once('include/MVC/View/views/view.list.php');

class WorkSchedulesViewList extends ViewList {

   public function preDisplay() {
      parent::preDisplay();
      $this->lv->actionsMenuExtraItems = $this->getExtraMenuActions();
   }

   function _displayJavascript() {
      parent::_displayJavascript();
      echo '<script type="text/javascript" src="' . getJSPath('modules/WorkSchedules/js/view.list.js') . '"></script>';
   }

   public function getExtraMenuActions() {
      $return_array = array();

      if ( ACLController::checkAccess('WorkSchedules', 'list', true) ) {
         $return_array[] = $this->buildMassConfirmationLink();
      }
      return $return_array;
   }

   public function buildMassConfirmationLink() {
      return $this->prepareLink("mass_confirmation", "massConfirmation", "LBL_MASS_CONFIRMATION");
   }

   protected function prepareLink($id, $js_function_name, $label) {
      global $mod_strings;
      $action = "<a href=\"javascript:void(0)\" id=\"{$id}_mass_action\" onclick=\"mintMassUpdateManager.{$js_function_name}();\">{$mod_strings[$label]}</a>";
      return $action;
   }

}
