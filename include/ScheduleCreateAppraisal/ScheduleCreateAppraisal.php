<?php

class ScheduleCreateAppraisal {

   protected $record_id;
   protected $module;
   protected $appraisal_name;

   public function __construct($record_id, $module, $appraisal_name) {
      $this->record_id = $record_id;
      $this->module = $module;
      $this->appraisal_name = $appraisal_name;
   }

   public function schedule() {
      global $current_user;
      $jq = new SugarJobQueue();
      $job = new SchedulersJob();
      $job->name = "Schedule Generate Appraisal and Appraisal items";
      $job->target = "class::GenerateAppraisalAppraisalItemsJob";
      $data = base64_encode(json_encode(array(
         'record_id' => $this->record_id,
         'module' => $this->module,
         'appraisal_name' => $this->appraisal_name,
      )));
      $job->data = $data;
      $job->assigned_user_id = $current_user->id;
      $jq->submitJob($job);
      return true;
   }

}
