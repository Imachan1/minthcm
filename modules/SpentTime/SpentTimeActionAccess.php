<?php

class SpentTimeActionAccess {

   protected $bean;
   protected $errors = array();
   protected $actions = array(
      'add_past_time' => array(
         'acl' => 'edit',
         'methods' => array(
            'isAdmin',
            'isSuperiorOfUserAssignedToWorkSchedule',
            'isMyWorkScheduleInCurrentMonth',
            'amISuperiorOfAnyOneAndItIsMyWorkSchedule'
         ),
         'conditions' => 'OR'
      ),
   );

   public function setBean(WorkSchedules $bean) {
      $this->bean = $bean;
   }

   public function checkAccess($action_name) {
      $this->errors = array();
      $action_name = strtolower($action_name);
      if ( isset($this->actions[$action_name]) ) {
         $acl = $this->bean->ACLAccess($this->actions[$action_name]['acl']);
         if ( !$acl ) {
            $errors[] = 'ERR_ACLACCESS_DENIED';
         }
         if ( is_array($this->actions[$action_name]['methods']) ) {
            if ( $this->actions[$action_name]['conditions'] == "OR" ) {
               $result = $this->conditionsOR($this->actions[$action_name]['methods']);
               $return = $result && $acl;
            } else if ( $this->actions[$action_name]['conditions'] == "AND" ) {
               $result = $this->conditionsAND($this->actions[$action_name]['methods']);
               $return = (count($this->errors) > 0 || !$result) ? false : true;
            }
         }
      }
      return array( 'result' => $return, 'errors' => $this->getErrors() );
   }

   protected function conditionsOR($methods) {
      $result = false;
      foreach ( $methods as $method ) {
         if ( method_exists($this, $method) && !$result ) {
            $method_result = $this->{$method}();
            if ( !$method_result ) {
               $this->errors[] = 'ERR_METHOD_ERROR_' . strtoupper($method);
            }
            $result = $result || $method_result;
         }
      }
      return $result;
   }

   protected function conditionsAND($methods) {
      foreach ( $methods as $method ) {
         if ( method_exists($this, $method) ) {
            if ( !$this->{$method}() ) {
               $this->errors[] = 'ERR_METHOD_ERROR_' . strtoupper($method);
            }
         } else {
            $this->errors[] = 'ERR_METHOD_DOES_NOT_EXISTS_' . strtoupper($method);
         }
      }
      return (count($this->errors) > 0) ? false : true;
   }

   public function getErrors() {
      return $this->errors;
   }

   protected function isAdmin() {
      global $current_user;
      return $current_user->isAdmin();
   }

   protected function isMyWorkScheduleInCurrentMonth() {
      global $current_user, $timedate;
      $now = new SugarDateTime();
      $date = SugarDateTime::createFromFormat($timedate->get_date_format(), $this->bean->schedule_date);
      return ($this->bean->assigned_user_id == $current_user->id && $date->format('m') == $now->format('m'));
   }

   protected function isSuperiorOfUserAssignedToWorkSchedule() {
      global $current_user;
      $assigned_user = BeanFactory::getBean('Users', $this->bean->assigned_user_id);
      return ($current_user->id == $assigned_user->reports_to_id);
   }

   protected function amISuperiorOfAnyOneAndItIsMyWorkSchedule() {
      global $current_user, $db;
      $sql = "SELECT id FROM users WHERE reports_to_id='{$current_user->id}' AND deleted=0";
      $superior = $db->getOne($sql);
      return ($this->bean->assigned_user_id == $current_user->id && $superior);
   }

}
