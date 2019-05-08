<?php

class WorkSchedulesApi {

   public function canChangeTypeToWorkOff($id, $type) {
      global $db;
      $result = true;
      $work_off_types = array(
         'holiday',
         'sick',
         'occasional_leave',
         'leave_at_request',
         'overtime',
         'excused_absence',
      );
      if ( !empty($id) && !empty($type) && in_array($type, $work_off_types) ) {
         $sql = "
            SELECT
               id
            FROM
               workschedules_spenttime
            WHERE
               workschedule_id = '{$id}' AND
               deleted = 0";
         if ( !empty($db->getOne($sql)) ) {
            $result = false;
         }
      }
      return $result;
   }

   public function checkWorkScheduleCreatedByPeriodicity($data) {
      require_once 'modules/Calendar/CalendarUtils.php';
      global $db, $timedate;
      if ( !empty($data['data']) && !empty($data['data']['date_start']) ) {
         $repeatArr = CalendarUtils::build_repeat_sequence($data['data']['date_start'], $data['data']);
         $date_interval = sprintf('+%d hour +%d minutes', $data['data']['duration_hours'], $data['data']['duration_minutes']);
         foreach ( $repeatArr as $repeat ) {
            $db_date_start = $timedate->to_db($repeat);
            $db_date_end = $timedate->to_db(date('Y-m-d H:i', strtotime($date_interval, strtotime($db_date_start))));
            $query = "
               SELECT COUNT(id)
               FROM workschedules
               WHERE assigned_user_id = '{$data['data']['assigned_user_id']}'
                 AND date_start < '{$db_date_end}'    
                 AND date_end > '{$db_date_start}'
                 AND deleted = 0
               ";
            if ( !empty($data['data']['record_id']) ) {
               $query .= "AND id != '{$data['data']['record_id']}'";
            }
            if ( $db->getOne($query) > 0 ) {
               return substr($repeat, 0, 10);
            }
         }
      }
      return null;
   }

}
