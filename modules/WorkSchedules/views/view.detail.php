<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}


require_once('include/MVC/View/views/view.detail.php');

class WorkSchedulesViewDetail extends ViewDetail {

   private function assignStrings() {
      global $app_list_strings, $mod_strings;
      $this->ss->assign('APPLIST', $app_list_strings);
      $this->ss->assign('MOD', $mod_strings);
   }

   public function preDisplay() {
      $this->assignStrings();
      parent::preDisplay();
   }

   public function display() {
      global $current_user;
      $this->dv->ss->assign('CURRENT_USER_IS_ADMIN', is_admin($current_user));
      return parent::display();
   }

}
