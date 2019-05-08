<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

require_once('include/generic/SugarWidgets/SugarWidgetSubPanelTopSelectButton.php');

class SugarWidgetSubPanelCustomTopSelectButton extends SugarWidgetSubPanelTopSelectButton {

   public function display($widget_data, $additionalFormFields = NULL, $nonbutton = false) {
      if ( isset($_REQUEST['module']) && $_REQUEST['module'] == 'SecurityGroups' && isset($_REQUEST['record']) && $this->isGroupPrivate($_REQUEST['record']) ) {
         return;
      }
      return parent::display($widget_data, $additionalFormFields, $nonbutton);
   }

   public function isGroupPrivate($group_id) {
      $group = BeanFactory::getBean('SecurityGroups', $group_id);
      if ( $group && $group->id && $group->group_type == 'private' ) {
         return true;
      }
      return false;
   }

}
