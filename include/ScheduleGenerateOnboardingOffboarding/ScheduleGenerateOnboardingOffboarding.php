<?php

class ScheduleGenerateOnboardingOffboarding {

   protected $module_name;
   protected $template_id;
   protected $employee_id;
   protected $date_start;

   public function __construct($module_name, $template_id, $employee_id, $date_start) {
      $this->module_name = $module_name;
      $this->template_id = $template_id;
      $this->employee_id = $employee_id;
      $this->date_start = $date_start;
   }

   public function schedule() {
      global $current_user;
      $jq = new SugarJobQueue();
      $job = new SchedulersJob();
      $job->name = "Schedule Generate Onboarding/Offboarding";
      $job->target = "class::GenerateOnboardingOffboardingJob";
      $data = base64_encode(json_encode(array(
         'module_name' => $this->module_name,
         'template_id' => $this->template_id,
         'employee_id' => $this->employee_id,
         'date_start' => $this->date_start,
      )));
      $job->data = $data;
      $job->assigned_user_id = $current_user->id;
      $jq->submitJob($job);
      return true;
   }

}
