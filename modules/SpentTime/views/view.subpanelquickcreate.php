<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

require_once('include/EditView/SubpanelQuickCreate.php');

class SpentTimeSubpanelQuickCreate extends SubpanelQuickCreate {

   protected $user;

   public function __construct($module) {
      $this->user = $GLOBALS['current_user'];
      parent::__construct($module);
   }

   public function process($module) {
      if ( !$this->ev->focus->assigned_user_id ) {
         $this->ev->focus->assigned_user_id = $this->user->id;
         $this->ev->focus->assigned_user_name = $this->user->name;
      }
      if ( !$this->ev->focus->employee_id ) {
         $this->ev->focus->employee_id = $this->user->id;
         $this->ev->focus->employee_name = $this->user->name;
      }
      return parent::process($module);
   }

}
