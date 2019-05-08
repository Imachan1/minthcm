<?php

class SpentTimeApi {

   public function getCountOfSpendTimeRecordsInGivenFrame($id, $workschedule_id, $date_start, $date_end, $frontend = false) {
      $frontend = !!$frontend;
      if ( !empty($workschedule_id) && !empty($date_start) && !empty($date_end) ) {
         if ( $frontend ) {
            global $timedate;
            $date_start = $timedate->to_db($date_start);
            $date_end = $timedate->to_db($date_end);
         }
         if ( empty($date_start) || empty($date_end) ) {
            $GLOBALS['log']->fatal('Empty date_start or date_end while validating spent time ' . $frontend ? 'FRONTEND' : 'BACKEND');
            $GLOBALS['log']->fatal('Arguments: ' . print_r(func_get_args(), true));
         } else {
            $db = DBManagerFactory::getInstance();
            $id_where = '';
            if ( isset($id) ) {
               $id_where = "st.id != '{$id}' AND ";
            }
            $sql = "SELECT COUNT(st.id) AS count FROM workschedules_spenttime ws "
                    . "LEFT JOIN spenttime st ON "
                    . "ws.spenttime_id=st.id "
                    . "AND ws.workschedule_id = '{$workschedule_id}' WHERE "
                    . "st.deleted = 0 AND "
                    . $id_where
                    . "((st.date_start <= '{$date_start}' AND st.date_end > '{$date_start}') OR "
                    . "(st.date_start < '{$date_end}' AND st.date_end >= '{$date_end}') OR "
                    . "(st.date_start < '{$date_start}' AND st.date_end > '{$date_start}') OR "
                    . "(st.date_start > '{$date_start}' AND st.date_end < '{$date_end}'))";
            return intval($db->getOne($sql));
         }
      }
      return 0;
   }

   public function canLogToWorkOffSchedule($workschedule_id) {
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
      if ( !empty($workschedule_id) ) {
         $sql = "
            SELECT
               type
            FROM
               workschedules
            WHERE
               id = '{$workschedule_id}' AND
               deleted = 0";
         $get_one = $db->getOne($sql);
         if ( !empty($get_one) && in_array($get_one, $work_off_types) ) {
            $result = false;
         }
      }
      return $result;
   }

   public function canLogTimeToPast($args) {
      SugarAutoLoader::requireWithCustom('modules/SpentTime/SpentTimeActionAccess.php');
      $checker = new SpentTimeActionAccess();
      $checker->setBean(BeanFactory::getBean('WorkSchedules', $args['workschedule_id']));
      return $checker->checkAccess('add_past_time');
   }

   public function getCurrentUserId($args) {
      global $current_user;
      if ( !empty($current_user->id) ) {
         return $current_user->id;
      }
      return false;
   }

}
