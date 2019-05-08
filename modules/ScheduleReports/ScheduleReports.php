<?php

class ScheduleReports extends Basic {

   public $new_schema = true;
   public $module_dir = 'ScheduleReports';
   public $object_name = 'ScheduleReports';
   public $table_name = 'schedulereports';
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
   public $frequency_performance;
   public $active;
   public $template_id;

   public function __construct() {
      parent::__construct();
   }

   public function bean_implements($interface) {
      if ( "ACL" === $interface ) {
         return true;
      } else {
         return false;
      }
   }

}
