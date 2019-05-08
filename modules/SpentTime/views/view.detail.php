<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

require_once('include/MVC/View/views/view.detail.php');

class SpentTimeViewDetail extends ViewDetail {

   public function display() {
      global $current_user;
      $this->dv->ss->assign('CURRENT_USER_IS_ADMIN', is_admin($current_user));
      return parent::display();
   }

}
