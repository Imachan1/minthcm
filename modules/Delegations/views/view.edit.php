<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}
require_once('include/MVC/View/views/view.edit.php');

class DelegationsViewEdit extends ViewEdit {

   function display() {
      global $mod_strings;
      if ( empty($this->bean->id) && $this->bean->purpose == '' ) {
         $this->bean->purpose = $mod_strings['LBL_PURPOSE_DEFAULT'];
      }
      parent::display();
   }

}
