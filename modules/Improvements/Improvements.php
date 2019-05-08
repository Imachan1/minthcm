<?php

require_once('modules/Improvements/SugarFeeds/ImprovementsFeed.php');

class Improvements extends Basic {

   public $new_schema = true;
   public $module_dir = 'Improvements';
   public $object_name = 'Improvements';
   public $table_name = 'improvements';
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

   public function save($check_notify = false) {
      $result = parent::save($check_notify);

      $improvements_feed = new ImprovementsFeed();
      $improvements_feed->pushFeed($this, null, null);

      return $result;
   }

}
