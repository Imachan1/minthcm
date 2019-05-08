<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

require_once('include/MVC/View/views/view.list.php');

class SpentTimeViewList extends ViewList {

   function preDisplay() {
      parent::preDisplay();
      $this->lv->showMassupdateFields = false;
      $this->lv->delete = false;
      $this->lv->export = false;
   }

}
