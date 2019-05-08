<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

require_once('include/MVC/View/views/view.detail.php');

class ResourcesViewDetail extends ViewDetail {

   public function preDisplay() {
      $this->assignSmartyVariables();
      parent::preDisplay();
   }

   protected function assignSmartyVariables() {
      $reservations_list_access = ACLController::checkAccess('ReservationsCalendar', 'list');
      $this->ss->assign('reservations_calendar_list_access', $reservations_list_access);
   }

}
