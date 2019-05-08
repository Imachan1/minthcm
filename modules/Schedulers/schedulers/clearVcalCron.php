<?php

$job_strings[] = 'clearVcalCron';

function clearVcalCron($data) {
   $processed_users = array();
   $db = DBManagerFactory::getInstance();
   $sqlResult = $db->query("SELECT user_id from meetings_users WHERE id = '{$data}' AND deleted = 0 ");
   while ( $row = $db->fetchByAssoc($sqlResult) ) {
      $user_id = $row['user_id'];
      if ( $user_id != '' && !isset($processed_users[$user_id]) ) {
         $user = BeanFactory::getBean("Users", $user_id);
         vCal::cache_sugar_vcal($user);
         $processed_users[$user_id] = true;
      }
   }
   return true;
}
