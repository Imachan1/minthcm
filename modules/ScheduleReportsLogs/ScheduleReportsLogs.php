<?php

require_once('modules/ScheduleReportsLogs/ScheduleReportsLogsTrait.php');

class ScheduleReportsLogs extends Basic {

   use ScheduleReportsLogsTrait;

   public $new_schema = true;
   public $module_dir = 'ScheduleReportsLogs';
   public $object_name = 'ScheduleReportsLogs';
   public $table_name = 'schedulereportslogs';
   public $importable = false;
   public $disable_row_level_security = true; // to ensure that modules created and deployed under CE will continue to function under team security if the instance is upgraded to PRO
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
   public $status;
   public $execute_data;

   public function __construct() {
      parent::__construct();
   }

   public function bean_implements($interface) {
      switch ( $interface ) {
         case 'ACL': return true;
      }
      return false;
   }

   public function ACLAccess($view, $is_owner = 'not_set', $in_group = 'not_set') {
      return $this->ACLAccessOverride($view, parent::ACLAccess($view, $is_owner, $in_group));
   }

}
