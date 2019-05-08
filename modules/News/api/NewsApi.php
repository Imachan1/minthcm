<?php

SugarAutoLoader::requireWithCustom('include/ScheduleGenerateUsersNews/ScheduleGenerateUsersNews.php');
SugarAutoLoader::requireWithCustom('include/SugarQueue/SugarJobQueue.php');

class NewsApi {

   public function setNewsStatus($args) {
      if ( isset($args['status']) && isset($args['news_id']) ) {
         $news = BeanFactory::getBean('News', $args['news_id']);
         if ( (!$news && empty($news->id)) || ($news->news_type == 'announcement' && !$this->scheduleGenerateUsersNews($news)) ) {
            return false;
         }
         $news->news_status = $args['status'];
         $news->save();
         return true;
      }
      return false;
   }

   protected function scheduleGenerateUsersNews($news) {
      try {
         $SGUN = new ScheduleGenerateUsersNews($news);
         return $SGUN->schedule();
      } catch ( Exception $ex ) {
         $GLOBALS['log']->fatal($ex->getTraceAsString());
         return false;
      }
   }

}
