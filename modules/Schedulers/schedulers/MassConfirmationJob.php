<?php

class MassConfirmationJob implements RunnableSchedulerJob {

   public function setJob(SchedulersJob $job) {
      $this->job = $job;
   }

   public function run($job_data) {
      ini_set('max_execution_time', -1);
      $ids = $this->parseMassParams($job_data);
      SugarAutoLoader::requireWithCustom('modules/WorkSchedules/MassConfirmation.php');
      $confirmator = new MassConfirmation();
      $confirmator->setIDs($ids);
      $confirmator->setUserId($this->job->assigned_user_id);
      $confirmator->confirm();
      ini_set('max_execution_time', 120);
      return true;
   }

   protected function parseMassParams($job_data_) {
      $job_data = json_decode(base64_decode($job_data_), true);
      $job_data['encoded_query'] = urldecode($job_data['encoded_query']);
      if ( $job_data['entire_list'] == '1' ) {
         $ids = getEntireListSearchForModule('AOS_Invoices', $job_data['encoded_query']);
      } else if ( !empty($job_data['ids']) ) {
         $ids = explode(',', $job_data['ids']);
      }
      return $ids;
   }

}
