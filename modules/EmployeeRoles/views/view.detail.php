<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

require_once('include/MVC/View/views/view.detail.php');

class EmployeeRolesViewDetail extends ViewDetail {

   public function preDisplay() {
      $this->assignSmartyVariables();
      parent::preDisplay();
   }

   protected function assignSmartyVariables() {
      $positions_edit_access = ACLController::checkAccess('Positions', 'edit');
      $this->ss->assign('positions_edit_access', $positions_edit_access);
   }

}
