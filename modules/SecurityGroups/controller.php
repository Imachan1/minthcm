<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

require_once('include/SugarQueue/SugarJobQueue.php');

class SecurityGroupsController extends SugarController {

   public function action_repair() {
      global $current_user;
      $jq = new SugarJobQueue();
      $job = new SchedulersJob();
      $job->name = "Repair Private Security Groups";
      $job->target = "class::RepairPrivateGroupsJob";
      $job->assigned_user_id = $current_user->id;

      try {
         $jq->submitJob($job);
         echo 'Repair Private Security Groups job added to Queue.';
      } catch ( Exception $ex ) {
         $GLOBALS['log']->fatal($ex->getTraceAsString());
      }
   }

}
