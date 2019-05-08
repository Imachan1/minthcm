<?php

require_once('modules/DashboardManager/src/DashboardDeployer.php');

class DashboardManager extends Basic {

   public $new_schema = true;
   public $module_dir = 'DashboardManager';
   public $object_name = 'DashboardManager';
   public $table_name = 'dashboardmanager';
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
   public $users_locked_dashboards;
   public $users_forced_tabs_dashboards;
   public $users_one_time_default_dashboards;
   public $is_loaded;

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
      if ( !empty($_REQUEST['customAction']) ) {
         $this->processCustomAction($_REQUEST['customAction']);
      }

      $this->serializeData();
      return parent::save($check_notify);
   }

   protected function processCustomAction($action) {
      global $log, $current_user;

      switch ( $action ) {
         case 'loadDashboards':
            $this->pages = $current_user->getPreference('pages', 'Home');
            $this->dashlets = $current_user->getPreference('dashlets', 'Home');
            $this->is_loaded = 1;
            break;
         case 'deployDashboards':
            $d = new DashboardDeployer($this);
            $d->deploy();
            break;
         default:
            $log->fatal('[EV][DM] Undefined customAction: ' . $action);
      }
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

}
