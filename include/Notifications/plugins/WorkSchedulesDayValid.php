<?php

class WorkSchedulesDayValid extends NotificationPlugin {

   public function run() {
      global $app_strings;
      $work_schedules = $this->getNotClosedWorkSchedules();
      foreach ( $work_schedules as $work_schedule ) {
         if ( !$work_schedule ) {
            continue;
         }
         if(empty($work_schedule['assigned_user_id'])){
            $GLOBALS['log']->fatal("WorkSchedulesDayValid: There is Work Schedule without assigned user id (WS id: ".$work_schedule['id'].")");
            continue;
         }

         $this->getNewNotification()
                 ->setDescription(sprintf(translate('LBL_APPROVED_ALERT', 'WorkSchedules'), $this->getWorkScheduleStartDate($work_schedule['id'])))
                 ->setAssignedUserId($work_schedule['assigned_user_id'])
                 ->setRelatedBean($work_schedule['id'], 'WorkSchedules')
                 ->saveAsAlert();
      }

      $wrong_notifications = NotificationManager::getWrongNotifications();
      foreach ( $wrong_notifications as $wrong_notification ) {
         $notification = BeanFactory::getBean('Alerts', $wrong_notification['id']);
         if ( !empty($notification->id) ) {
            $notification->mark_deleted($notification->id);
         }
      }
   }

   protected function getWorkScheduleStartDate($work_schedule_id) {
      $bean = BeanFactory::getBean('WorkSchedules', $work_schedule_id);
      $datetime = explode(' ', NotificationManager::toDbDatetime($bean->date_start));
      return $datetime[0];
   }

   protected function getNotClosedWorkSchedules() {
      global $db;
      $sql_result = $db->query("SELECT id, assigned_user_id FROM `workschedules` WHERE `status`!='Closed' AND `deleted`=0 AND `date_end` < CURDATE();");
      $result = array();
      while ( $result[] = $db->fetchByAssoc($sql_result) );
      return $result;
   }

}
