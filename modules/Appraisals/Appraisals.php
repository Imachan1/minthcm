<?php

require_once('modules/AppraisalItems/AppraisalItems.php');
require_once('modules/Appraisals/SugarFeeds/AppraisalsFeed.php');

class Appraisals extends Basic {

   public $new_schema = true;
   public $module_dir = 'Appraisals';
   public $object_name = 'Appraisals';
   public $table_name = 'appraisals';
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
      $af = new AppraisalsFeed();
      $af->pushFeed($this, null, null);
      AppraisalItems::parentSave($this);
   }

   public function mark_deleted($id) {
      AppraisalItems::parentDelete($this);
      parent::mark_deleted($id);
   }

}
