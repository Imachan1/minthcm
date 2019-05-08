<?php

require_once 'modules/Resources/SugarFeeds/ResourcesFeed.php';

class Resources extends Basic {

   public $new_schema = true;
   public $module_dir = 'Resources';
   public $object_name = 'Resources';
   public $table_name = 'resources';
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
   public $type;
   public $unavailable;

   public function bean_implements($interface) {
      if ( $interface == 'ACL' ) {
         return true;
      }
      return false;
   }

   protected function postSave() {
      $rf = new ResourcesFeed();
      $rf->pushFeed($this, null, null);
   }

}
