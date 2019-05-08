<?php

class NewsDisplayDateUpdater {

   const LIMIT = 4;

   public function start() {
      $news_ids = $this->getNewsIds();
      $this->updateNewsDisplayDate($news_ids);
   }

   protected function getNewsIds() {
      global $db;
      $results = array();
      $sql = "SELECT id FROM news WHERE news_type = 'reminder' AND news_status = 'published' AND (display_date IS NULL OR display_date = '' OR display_date < CURDATE() - INTERVAL 30 DAY) AND deleted = 0 ORDER BY display_date ASC LIMIT " . self::LIMIT;
      $result = $db->query($sql);
      while ( $row = $db->fetchByAssoc($result) ) {
         $results[] = $row['id'];
      }
      return $results;
   }

   protected function updateNewsDisplayDate($news_ids) {
      global $timedate;
      foreach ( $news_ids as $news_id ) {
         $news = BeanFactory::getBean('News', $news_id);
         if ( $news && !empty($news->id) ) {
            $news->display_date = $timedate->nowDbDate();
            $news->save();
         }
      }
   }

}
