<?php

class ScheduleGenerateUsersNews {

   protected $news_bean;

   public function __construct($news_bean) {
      $this->news_bean = $news_bean;
   }

   public function schedule() {
      global $current_user;
      $jq = new SugarJobQueue();
      $job = new SchedulersJob();
      $job->name = "Schedule Generate User's News";
      $job->target = "class::GenerateUsersNewsJob";
      $data = $this->news_bean->id;
      $job->data = $data;
      $job->assigned_user_id = $current_user->id;
      $jq->submitJob($job);
      return true;
   }

}
