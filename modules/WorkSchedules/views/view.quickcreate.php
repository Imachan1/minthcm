<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

require_once('include/MVC/View/views/view.edit.php');

class WorkSchedulesViewQuickcreate extends ViewQuickcreate {

   public function display() {
      global $current_user;
      $this->ev->ss->assign('CURRENT_USER_IS_ADMIN', is_admin($current_user));
      return parent::display();
   }

}
