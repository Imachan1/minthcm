<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

require_once('modules/SugarFeed/feedLogicBase.php');

class TrainingsFeed extends FeedLogicBase {

   public $module = 'Trainings';

   public function pushFeed($bean, $event, $arguments) {
      if ( empty($bean->fetched_row) && !isset($bean->feedPushed) ) {
         $text = '{SugarFeed.LBL_CREATED_TRAININGS} [' . $bean->module_dir . ':' . $bean->id . ':' . $bean->name . ']';
         SugarFeed::pushFeed2($text, $bean);
         $bean->feedPushed = 1;
      }
   }

}
