<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

require_once('include/MVC/View/views/view.edit.php');

class SpentTimeViewEdit extends ViewEdit {

   protected $user;

   public function __construct() {
      if ( !empty($_REQUEST['workschedules_id']) ) {
         $ws = BeanFactory::getBean('WorkSchedules', $_REQUEST['workschedules_id']);
         $this->user = BeanFactory::getBean('Users', $ws->assigned_user_id);
      } else {
         $this->user = $GLOBALS['current_user'];
      }
      parent::__construct();
   }

   public function display() {
      global $current_user;
      if ( ($this->bean->assigned_user_id && is_null($this->bean->employee_id)) || !$this->bean->assigned_user_id ) {
         $this->bean->assigned_user_id = $this->user->id;
         $this->bean->assigned_user_name = $this->user->name;
      } elseif ( $this->bean->employee_id && $this->bean->employee_id != $current_user->id ) {
         $this->bean->assigned_user_id = $this->bean->employee_id;
         $this->bean->assigned_user_name = $this->bean->employee_name;
      }

      if ( !$this->bean->employee_id ) {
         $this->bean->employee_id = $this->user->id;
         $this->bean->employee_name = $this->user->name;
      }
      $this->ev->ss->assign('CURRENT_USER_IS_ADMIN', is_admin($current_user));
      return parent::display();
   }

}
