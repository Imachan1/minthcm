<?php

class GenerateAppraisalAppraisalItemsJob implements RunnableSchedulerJob {

   public function setJob(SchedulersJob $job) {
      $this->job = $job;
   }

   public function run($job_data) {
      require_once('include/GenerateAppraisalAppraisalItems/TransformAppraisal.php');
      $data = json_decode(base64_decode($job_data), true);
      $GAAI = new TransformAppraisal($data);
      $GAAI->runCronJob();
      return true;
   }

}
