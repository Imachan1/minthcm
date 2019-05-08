<?php

class RepairPrivateGroupsJob implements RunnableSchedulerJob {

   public function setJob(SchedulersJob $job) {
      $this->job = $job;
   }

   public function run($job_data) {
      require_once('modules/SecurityGroups/RepairPrivateGroups.php');
      $RPG = new RepairPrivateGroups();
      $RPG->repair();
      return true;
   }

}
