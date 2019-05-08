<?php

require_once 'modules/Reservations/SugarFeeds/ReservationsFeed.php';

class Reservations extends Basic {

   public $new_schema = true;
   public $module_dir = 'Reservations';
   public $object_name = 'Reservations';
   public $table_name = 'reservations';
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
   public $starting_date;
   public $ending_date;

   public function bean_implements($interface) {
      if ( $interface == 'ACL' ) {
         return true;
      }
      return false;
   }

   protected function postSave() {
      $rf = new ReservationsFeed();
      $rf->pushFeed($this, null, null);
   }

}
