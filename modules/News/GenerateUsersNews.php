<?php

if ( !defined('sugarEntry') ) {
   define('sugarEntry', true);
}

class GenerateUsersNews {

   protected $record_id;

   public function __construct($data) {
      $this->record_id = (isset($data)) ? $data : null;
   }

   public function generate() {
      $news = BeanFactory::getBean('News', $this->record_id);
      if ( $news && !empty($news->id) ) {
         $organizational_units_ids = $news->getRelatedOrganizationalUnitsIDs();
         $organizationalunits_controller = ControllerFactory::getController('OrganizationalUnits');
         $users_ids = $organizationalunits_controller->getActiveUsers($organizational_units_ids);
         $this->createOrUpdateUsersNews($users_ids);
         $this->addUsersPrivateGroupsToNews($users_ids);
      }
   }

   protected function createOrUpdateUsersNews($users_ids) {
      foreach ( $users_ids as $user_id ) {
         $users_news_id = $this->getUsersNewsForUser($user_id);
         if ( $users_news_id ) {
            $this->updateUsersNewsReadFlag($users_news_id);
         } else {
            $this->createUsersNewsForUser($user_id);
         }
      }
   }

   protected function getUsersNewsForUser($user_id) {
      global $db;
      $sql = "SELECT id FROM usersnews WHERE news_id='{$this->record_id}' AND assigned_user_id='{$user_id}' AND deleted = 0";
      $result = $db->getOne($sql);
      return ($result) ? $result : false;
   }

   protected function updateUsersNewsReadFlag($users_news_id) {
      $users_news = BeanFactory::getBean('UsersNews', $users_news_id);
      if ( $users_news && !empty($users_news->id) ) {
         $users_news->news_read = false;
         $users_news->save();
      }
   }

   protected function createUsersNewsForUser($user_id) {
      $user = BeanFactory::getBean('Users', $user_id);
      $news = BeanFactory::getBean('News', $this->record_id);
      if ( $user && !empty($user->id) && $news && !empty($news->id) ) {
         $users_news = BeanFactory::newBean('UsersNews');
         $users_news->news_id = $news->id;
         $users_news->news_name = $news->name;
         $users_news->assigned_user_id = $user->id;
         $users_news->assigned_user_name = $user->name;
         $users_news->save();
      }
   }

   protected function addUsersPrivateGroupsToNews($users_ids) {
      $news = BeanFactory::getBean('News', $this->record_id);
      if ( $news && !empty($news->id) && $news->load_relationship('SecurityGroups') ) {
         $groups = $news->SecurityGroups->get();
         $news->SecurityGroups->delete($groups);
         array_push($users_ids, $news->assigned_user_id);
         $news->SecurityGroups->add($this->getPrivateGroups($users_ids));
      }
   }

   protected function getPrivateGroups($users_ids) {
      global $db;
      $results = array();
      $sql = "SELECT id FROM securitygroups WHERE group_type='private' AND deleted = 0 AND assigned_user_id IN ('" . implode('\',\'', $users_ids) . "')";
      $result = $db->query($sql);
      while ( $row = $db->fetchByAssoc($result) ) {
         $results[] = $row['id'];
      }
      return $results;
   }

}
