<?php

class DashboardHistory extends Basic {

   public $new_schema = true;
   public $module_dir = 'DashboardHistory';
   public $object_name = 'DashboardHistory';
   public $table_name = 'dashboardhistory';
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
      if ( "ACL" === $interface ) {
         return true;
      } else {
         return false;
      }
   }

}
