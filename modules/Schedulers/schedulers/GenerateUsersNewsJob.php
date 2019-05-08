<?php

class GenerateUsersNewsJob implements RunnableSchedulerJob {

   public function setJob(SchedulersJob $job) {
      $this->job = $job;
   }

   public function run($job_data) {
      require_once('modules/News/GenerateUsersNews.php');
      $GUN = new GenerateUsersNews($job_data);
      $GUN->generate();
      return true;
   }

}
