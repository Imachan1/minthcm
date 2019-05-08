<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

require_once 'include/generic/SugarWidgets/SugarWidgetSubPanelTopButtonQuickCreate.php';

class SugarWidgetSubPanelTopButtonQuickCreate_Reservations extends SugarWidgetSubPanelTopButtonQuickCreate {

   public function __construct(&$layout_manager) {
      parent::__construct($layout_manager);
      global $app_strings;
      $this->module = 'Reservations';
      $this->title = $app_strings['LBL_NEW_BUTTON_TITLE'];
      $this->access_key = $app_strings['LBL_NEW_BUTTON_KEY'];
      $this->form_value = translate('LBL_NEW_BUTTON_LABEL', $this->module);
      $this->acl = 'edit';
   }

   public function display($defines, $additionalFormFields = null, $nonbutton = false) {

      global $app_strings;
      $title = $app_strings['LBL_NEW_BUTTON_TITLE'];
      $value = $app_strings['LBL_NEW_BUTTON_LABEL'];
      $modules_to_change_dates = array( 'Delegations', 'Meetings' );
      $parent_bean = $defines['subpanel_definition']->parent_bean;
      $module_name = $parent_bean->module_name;

      if ( in_array($module_name, $modules_to_change_dates) ) {
         if ( $module_name == 'Delegations' ) {
            $end = $parent_bean->fetched_row['end_date'];
            $start = $parent_bean->fetched_row['start_date'];
         } elseif ( $module_name == 'Meetings' ) {
            $end = $parent_bean->fetched_row['date_end'];
            $start = $parent_bean->fetched_row['date_start'];
         }
         $additionalFormFields['starting_date'] = $start;
         $additionalFormFields['ending_date'] = $end;
      }


      $button = $this->_get_form($defines, $additionalFormFields);
      $button .= "<input title='$title' class='button' type='submit' " .
         "name='{$this->getWidgetId()}' id='{$this->getWidgetId()}' value='  $value  '/>\n";
      $button .= "</form>";


      return $button;
   }

}
