<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

require_once('modules/SugarFeed/feedLogicBase.php');

class OnboardingsFeed extends FeedLogicBase {

   public $module = 'Onboardings';

   public function pushFeed($bean, $event, $arguments) {
      if ( empty($bean->fetched_row) && !isset($bean->feedPushed) ) {
         $text = '{SugarFeed.LBL_CREATED_ONBOARDINGS} [' . $bean->module_dir . ':' . $bean->id . ':' . $bean->name . ']';
         SugarFeed::pushFeed2($text, $bean);
         $bean->feedPushed = 1;
      }
   }

}
