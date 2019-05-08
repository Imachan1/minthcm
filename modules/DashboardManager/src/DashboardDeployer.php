<?php

class DashboardDeployer {

   protected $_bean;
   protected $_log;
   protected $_related_history_record;
   protected $_user_count;

   public function __construct(DashboardManager $dashboard = null) {
      if ( isset($dashboard) ) {
         $this->setBean($dashboard);
      }
      $this->_log = $GLOBALS['log'];
   }

   public function setBean(DashboardManager $dashboard) {
      $this->_bean = $dashboard;
      return $this;
   }

   public function deploy() {
      try {
         $this->_user_count = 0;
         $this->_related_history_record = $this->_createHistoryRecord();

         $this->_deployOneTimeDashboards();
         $this->_deployForcedTabsDashboards();
         $this->_deployLockedDashboards();

         $this->_related_history_record->user_count = $this->_user_count;
         $this->_related_history_record->save(false);
      } catch ( Exception $e ) {
         $this->_log->fatal('[DM] DashboardDeployer failed: ' . $e->getMessage());
         return false;
      }
      return true;
   }

   protected function _deployOneTimeDashboards() {
      if ( $this->_bean->load_relationship('users_one_time_default_dashboards') ) {
         $users = $this->_bean->users_one_time_default_dashboards->getBeans();
         foreach ( $users as $user ) {
            $this->_deploy($user, $this->_bean->pages, $this->_bean->dashlets);
         }
      } else {
         throw new Exception('Can not load relationship: users_one_time_default_dashboards');
      }
   }

   protected function _deployForcedTabsDashboards() {
      if ( $this->_bean->load_relationship('users_forced_tabs_dashboards') ) {
         $users = $this->_bean->users_forced_tabs_dashboards->getBeans();

         $pages = $this->_bean->pages;
         $dashlets = $this->_bean->dashlets;

         foreach ( $pages as $key => $page ) {
            $pages[$key]['DMForced'] = true;
         }
         foreach ( $users as $user ) {
            $this->_deploy($user, $pages, $dashlets);
         }
      } else {
         throw new Exception('Can not load relationship: users_forced_tabs_dashboards');
      }
   }

   protected function _deployLockedDashboards() {
      if ( $this->_bean->load_relationship('users_locked_dashboards') ) {
         $users = $this->_bean->users_locked_dashboards->getBeans();

         $pages = $this->_bean->pages;
         $dashlets = $this->_bean->dashlets;
         foreach ( $pages as $key => $page ) {
            $pages[$key]['DMLocked'] = true;
         }

         foreach ( $users as $user ) {
            $this->_deploy($user, $pages, $dashlets);
         }
      } else {
         throw new Exception('Can not load relationship: users_locked_dashboards');
      }
   }

   protected function _deploy(User $user, $pages, $dashlets) {
      $this->_makeDashboardCopy($user);
      $this->_user_count++;

      $user->setPreference('pages', $pages, 0, 'Home');
      $user->setPreference('dashlets', $dashlets, 0, 'Home');
      $user->savePreferencesToDB();
   }

   protected function _makeDashboardCopy(User $user) {
      $user->_userPreferenceFocus->reloadPreferences('Home');

      $bean = BeanFactory::getBean('DashboardBackups');
      $bean->assigned_user_id = $user->id;
      $bean->pages = $user->getPreference('pages', 'Home');
      $bean->dashlets = $user->getPreference('dashlets', 'Home');
      $bean->name = $this->_related_history_record->name . ' - ' . $user->first_name . ' ' . $user->last_name;
      $bean->dashboardmanager_id = $this->_bean->id;
      $bean->dashboardhistory_id = $this->_related_history_record->id;
      $bean->save(false);
   }

   protected function _createHistoryRecord() {
      global $current_user, $timedate;
      $bean = BeanFactory::getBean('DashboardHistory');
      if ( $bean ) {
         $bean->id = create_guid();
         $bean->new_with_id = true;
         $bean->name = $this->_bean->name . ' - ' . $timedate->nowDbDate();
         $bean->assigned_user_id = $current_user->id;
         $bean->dashboardmanager_id = $this->_bean->id;
         return $bean;
      } else {
         throw new Exception('Can not create DashboardHistory bean');
      }
   }

}
