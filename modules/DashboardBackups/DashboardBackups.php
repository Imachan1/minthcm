<?php

class DashboardBackups extends Basic {

   public $new_schema = true;
   public $module_dir = 'DashboardBackups';
   public $object_name = 'DashboardBackups';
   public $table_name = 'dashboardbackups';
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
   public $encoded_pages;
   public $encoded_dashlets;
   public $dashboardbackups_dashboardmanager;
   public $dashboardmanager_name;
   public $dashboardmanager_id;

   public function bean_implements($interface) {
      if ( "ACL" === $interface ) {
         return true;
      } else {
         return false;
      }
   }

   public function retrieve($id = -1, $encode = true, $deleted = true) {
      $parent = parent::retrieve($id, $encode, $deleted);
      $this->unserializeData();
      return $parent;
   }

   public function save($check_notify = false) {
      if ( !empty($_REQUEST['customAction']) && $_REQUEST['customAction'] === 'restoreBackup' ) {
         $this->_restoreBackup();
      }

      $this->serializeData();
      return parent::save($check_notify);
   }

   public function serializeData() {
      $this->encoded_pages = base64_encode(serialize($this->pages));
      $this->encoded_dashlets = base64_encode(serialize($this->dashlets));
   }

   public function unserializeData() {
      $this->pages = unserialize(base64_decode($this->encoded_pages));
      $this->dashlets = unserialize(base64_decode($this->encoded_dashlets));

      $this->pages = empty($this->pages) ? array() : $this->pages;
      $this->dashlets = empty($this->dashlets) ? array() : $this->dashlets;
   }

   protected function _restoreBackup() {
      if ( !empty($this->assigned_user_id) ) {
         $user = BeanFactory::getBean('Users', $this->assigned_user_id);
         if ( isset($user->id) && $user->id === $this->assigned_user_id ) {
            $user->setPreference('pages', $this->pages, 0, 'Home');
            $user->setPreference('dashlets', $this->dashlets, 0, 'Home');
            $user->savePreferencesToDB();
         }
      }
   }

}
