<?php

class UsersNewsApi {

   public function getNewsForUser() {
      return array_merge($this->getAnnouncements(), $this->getReminders());
   }

   public function createOrUpdateUsersNews($args) {
      if ( !empty($args['record_id']) ) {
         $news = BeanFactory::getBean('News', $args['record_id']);
         if ( $news && !empty($news->id) && !$this->updateUserNews($news->id, $news->news_type) && $news->news_type == 'reminder' ) {
            $this->createUserNews($news->id, $news->name);
         }
      }
   }

   protected function updateUserNews($news_id, $news_type) {
      global $current_user, $db, $timedate;
      $sql = "SELECT id FROM usersnews WHERE news_id = '{$news_id}' AND assigned_user_id = '{$current_user->id}' AND deleted = 0";
      $result = $db->getOne($sql);
      $user_news = BeanFactory::getBean('UsersNews', $result);
      if ( $user_news && !empty($user_news->id) ) {
         if ( $news_type == 'announcement' ) {
            $user_news->news_read = true;
         } else {
            $user_news->not_display = $timedate->nowDbDate();
         }
         $user_news->save();
         return true;
      }
      return false;
   }

   protected function createUserNews($news_id, $news_name) {
      global $current_user, $timedate;
      $user_news = BeanFactory::newBean('UsersNews');
      $user_news->news_id = $news_id;
      $user_news->news_name = $news_name;
      $user_news->assigned_user_id = $current_user->id;
      $user_news->assigned_user_name = $current_user->name;
      $user_news->not_display = $timedate->nowDbDate();
      $user_news->save();
   }

   protected function getAnnouncements() {
      global $current_user, $db;
      $results = array();
      $sql = "SELECT id, name, content_of_announcement, news_type FROM news WHERE id IN (SELECT news_id FROM usersnews WHERE news_read = 0 AND assigned_user_id = '{$current_user->id}' AND deleted = 0) AND news_type='announcement' AND news_status <> 'draft' AND deleted = 0";
      $result = $db->query($sql);
      while ( $row = $db->fetchByAssoc($result) ) {
         $results[] = new NewsInfo($row);
      }
      return $results;
   }

   protected function getReminders() {
      global $current_user, $db;
      $results = array();
      $sql = "SELECT n.id, n.name, n.content_of_announcement, n.news_type, un.id user_news_id FROM news n
LEFT JOIN usersnews un ON un.not_display > (CURDATE() - INTERVAL 30 DAY) AND un.news_id = n.id AND un.assigned_user_id = '{$current_user->id}' AND un.deleted = 0
WHERE n.news_type='reminder' AND n.display_date = CURDATE() AND n.news_status = 'published' AND n.deleted = 0 AND un.id IS NULL";
      $result = $db->query($sql);
      while ( $row = $db->fetchByAssoc($result) ) {
         $news = BeanFactory::getBean('News', $row['id']);
         if ( $news && !empty($news->id) && $news->canBeDisplayForUser($current_user->id) ) {
            $results[] = new NewsInfo($row);
         }
      }
      return $results;
   }

}

class NewsInfo {

   public $id;
   public $name;
   public $content_of_announcement;
   public $news_type;

   public function __construct($row) {
      $this->id = $row['id'];
      $this->name = $row['name'];
      $this->content_of_announcement = htmlspecialchars_decode($row['content_of_announcement']);
      $this->news_type = $row['news_type'];
   }

}
