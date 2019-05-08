<?php

require_once('modules/EmployeeRoles/SugarFeeds/EmployeeRolesFeed.php');

class EmployeeRoles extends Basic {

   public $new_schema = true;
   public $module_dir = 'EmployeeRoles';
   public $object_name = 'EmployeeRoles';
   public $table_name = 'employeeroles';
   public $importable = true;
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
      $result = false;
      if ( $interface === 'ACL' ) {
         $result = true;
      }
      return $result;
   }

   protected function postSave() {
      $ef = new EmployeeRolesFeed();
      $ef->pushFeed($this, null, null);
   }

}
