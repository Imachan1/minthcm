<?php

class WorkSchedulesNotPlandForTwoWeeks extends NotificationPlugin {

   const PLAN_FOR_DAYS = 10;

   public function run() {
      $work_schedules = $this->getNotPlannedWorkSchedules();
      foreach ( $work_schedules as $work_schedule ) {
         if ( $work_schedule == false ) {
            continue;
         }
         $this->getNewNotification()
                 ->setAssignedUserId($work_schedule['id'])
                 ->setRelatedBean('', 'WorkSchedules')
                 ->setDescription(translate('LBL_TWO_WEEKS_ALERT', 'WorkSchedules'))
                 ->saveAsAlert();
      }
   }

   protected function getNotPlannedWorkSchedules() {
      global $db;
      $work_days = $this->getWorkingDaysArray();
      $days_where = "workschedules.schedule_date IN('" . implode("','", $work_days) . "')";

      $query = "SELECT COUNT(users.id) as 'days',users.id FROM users LEFT JOIN workschedules ON workschedules.assigned_user_id=users.id  AND " . $days_where . " WHERE users.status='Active' GROUP BY users.id HAVING days<" . self::PLAN_FOR_DAYS;
      $sql_result = $db->query($query);

      $return_data = array();
      while ( $return_data[] = $db->fetchByAssoc($sql_result) );
      return $return_data;
   }

   protected function getWorkingDaysArray() {
      $non_working_days = $this->getNonWorkingDays();
      $work_days = array();
      $shift = 0;
      while ( count($work_days) < self::PLAN_FOR_DAYS ) {
         $date = date("Y-m-d", strtotime("+ $shift days"));
         if ( date('w', strtotime($date)) == 0 || date('w', strtotime($date)) == 6 || in_array($date, $non_working_days) ) {
            $shift++;
            continue;
         }
         $work_days[] = $date;
         $shift++;
      }
      return $work_days;
   }

   protected function getNonWorkingDays() {
      global $db;
      $non_working_days = array();
      $result = $db->query("SELECT date FROM `nonworkingdays` WHERE `deleted`=0;");
      while ( $row = $db->fetchByAssoc($result) ) {
         $non_working_days[] = $row['date'];
      }
      return $non_working_days;
   }

}
