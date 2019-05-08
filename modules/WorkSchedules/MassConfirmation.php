<?php

SugarAutoLoader::requireWithCustom('modules/SchedulersJobs/SchedulersJob.php');
SugarAutoLoader::requireWithCustom('include/SugarQueue/SugarJobQueue.php');
SugarAutoLoader::requireWithCustom('include/Notifications/Notification.php');

class MassConfirmation {

   protected $user_id = '';
   protected $ids = [];
   protected $errors = [];
   protected $success = [];

   public function setIDs(Array $ids) {
      $this->ids = $ids;
   }

   public function setUserId($id) {
      $this->user_id = $id;
   }

   public function confirm() {
      $this->success = [];
      $this->errors = [];
      foreach ( $this->ids as $id ) {
         $work_schedule = BeanFactory::getBean('WorkSchedules', $id);
         if ( $work_schedule->confirm() ) {
            $this->success[] = $work_schedule;
         } else {
            $this->errors[] = $work_schedule;
         }
      }
      $this->createAlert();
   }

   public function createAlert() {
      $notification = new Notification();
      $notification->setAssignedUserId($this->user_id);

      $notification->setDescription($this->getAlertDescription());
      $notification->disableUniqueValidation();
      $notification->saveAsAlert();
   }

   protected function getAlertDescription() {
      $success_count = count($this->success);
      $total_count = $success_count + count($this->errors);
      $ret = $GLOBALS['app_strings']['LBL_WSMASSCONFIRMATION_ALERT'];
      $ret .= ' (' . $success_count . '/' . $total_count . ')';
      return $ret;
   }

   public static function schedule($ids, $encoded_query, $entire_list) {
      global $current_user;
      $data = base64_encode(json_encode(
            array(
               'ids' => $ids,
               'encoded_query' => $encoded_query,
               'entire_list' => $entire_list,
            )
      ));
      $job = new SchedulersJob();
      $job->name = "Mass Confirmation";
      $job->target = "class::MassConfirmationJob";
      $job->assigned_user_id = $current_user->id;
      $job->data = $data;
      $jq = new SugarJobQueue();
      $jq->submitJob($job);
   }

}
