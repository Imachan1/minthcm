<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

class ReservationsController extends SugarController {

   protected function post_save() {
      parent::post_save();
      if ( $this->return_module == 'ReservationsCalendar' && $this->return_action == 'index' ) {
         $this->set_redirect("index.php?module=ReservationsCalendar&action=index");
      }
   }

}
