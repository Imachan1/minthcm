<?php

class PeriodsOfEmployment extends Basic {

   public $new_schema = true;
   public $module_dir = 'PeriodsOfEmployment';
   public $object_name = 'PeriodsOfEmployment';
   public $table_name = 'periodsofemployment';
   public $importable = false;
   public $id;
   public $name;
   public $date_entered;
   public $date_modified;
   public $modified_user_id;
   public $modified_by_name;
   public $created_by;
   public $created_by_name;
   public $description;
   public $deleted;
   public $created_by_link;
   public $modified_user_link;
   public $assigned_user_id;
   public $assigned_user_name;
   public $assigned_user_link;
   public $SecurityGroups;

   public function bean_implements($interface) {
      if ( $interface === 'ACL' ) {
         return true;
      } else {
         return false;
      }
   }

   public function ACLAccess($view, $is_owner = 'not_set', $in_group = 'not_set') {

      $limited_view_types = array( 'delete', 'editview', 'edit' );

      if ( in_array(strtolower($view), $limited_view_types) ) {
         $result = false;
      } else {
         $result = parent::ACLAccess($view, $is_owner, $in_group);
      }

      return $result;
   }

   public function save($check_notify = false) {
      $this->name = $this->getName();
      return parent::save($check_notify);
   }

   protected function getName() {
      $employee_bean = BeanFactory::getBean('Employees', $this->employee_id);
      global $sugar_config;
      $start_date = getDateTimeObject($this->period_starting_date);
      $end_date = getDateTimeObject($this->period_ending_date);
      return $employee_bean->first_name . ' '
              . $employee_bean->last_name . ' '
              . $start_date->format($sugar_config['default_date_format']) . ' - '
              . (!empty($end_date) ? $end_date->format($sugar_config['default_date_format']) : '...' );
   }

}
