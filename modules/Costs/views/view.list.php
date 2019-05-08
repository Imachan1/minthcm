<?php

require_once('include/MVC/View/views/view.list.php');

class CostsViewList extends ViewList {

   function __construct() {
      parent::__construct();
   }

   function display() {

      global $current_user;
      $currency = new Currency();
      $currency->retrieve($current_user->getPreference("currency"));

      $this->ss->assign('CURRENCY_SYMBOL', $currency->iso4217);

      parent::display();
   }

}
