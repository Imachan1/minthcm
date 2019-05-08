<?php

class GenerateOnboardingOffboardingJob implements RunnableSchedulerJob {

   public function setJob(SchedulersJob $job) {
      $this->job = $job;
   }

   public function run($job_data) {
      require_once('modules/OnboardingTemplates/GenerateOnboardingOffboarding.php');
      $data = json_decode(base64_decode($job_data), true);
      $GOO = new GenerateOnboardingOffboarding(array_merge($data, ['user_scheduled_onboarding_id' => $this->job->assigned_user_id]));
      $GOO->generate();
      return true;
   }

}
