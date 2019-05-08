<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

class SpentTimeController extends SugarController {

   protected function getLastDate(SugarBean $bean) {
      global $db, $timedate;

      $query = "SELECT
                  date_end date
               FROM
                  workschedules_spenttime ws
               INNER JOIN 
                  spenttime st 
               ON
                  st.id = ws.spenttime_id
                  AND st.deleted=0
                  AND ws.deleted=0 
               WHERE
                  ws.workschedule_id = '{$bean->id}'
                  AND ws.workschedule_id != ''
                  AND st.deleted = 0
                  AND ws.deleted = 0
               ORDER BY
                  st.date_end DESC";

      $res = $db->fetchOne($query);

      if ( !$res ) {
         $query = "SELECT 
                     date_start date
                  FROM
                  workschedules
                  WHERE
                     id = '{$bean->id}'
                     AND deleted = 0
                  ORDER BY 
                     date_start DESC";
         $res = $db->fetchOne($query);
      }
      return $timedate->fromDb($res['date']);
   }

   public function action_getDate() {
      global $timedate;

      ob_clean();
      $workschedule = BeanFactory::getBean("WorkSchedules");
      if ( $workschedule && $workschedule->retrieve($_REQUEST['record']) ) {
         $date_start = $this->getLastDate($workschedule);
         $time_date_start = $timedate->splitTime($timedate->asUser($date_start), $timedate->get_date_time_format());

         $date_end = $timedate->fromUser($workschedule->date_end);
         if ( $date_end && $date_end->format('Y-m-d') == date('Y-m-d') ) {
            $date_end = $timedate->fromUser(date($timedate->get_date_time_format()));
         }
         $time_date_end = $timedate->splitTime($timedate->asUser($date_end), $timedate->get_date_time_format());

         $return_array = array(
            'scheduleDateStart' => $timedate->asUserDate($date_start),
            'scheduleDateEnd' => $timedate->asUserDate($date_end),
            'scheduleDateLastMin' => array('H' => $time_date_start['h'], 'M' => $time_date_start['m']),
            'scheduleDateEndMin' => array('H' => $time_date_end['h'], 'M' => $time_date_end['m']),
         );
         exit(json_encode($return_array));
      }
   }

   public function action_isUniqueSpentTime() {
      global $timedate;
      $result = true;
      $record_id = isset($_REQUEST['record_id']) ? $_REQUEST['record_id'] : '';
      $assigned_user_id = isset($_REQUEST['assigned_user_id']) ? $_REQUEST['assigned_user_id'] : '';
      $date_start = isset($_REQUEST['date_start']) ? $_REQUEST['date_start'] : '';
      $date_end = isset($_REQUEST['date_end']) ? $_REQUEST['date_end'] : '';
      if ( $assigned_user_id != '' && $date_start != '' && $date_end != '' ) {
         $db_date_start = $timedate->to_db($date_start);
         $db_date_end = $timedate->to_db($date_end);
         $query = "
            SELECT
               COUNT(id) as counter
            FROM
               spenttime
            WHERE
               assigned_user_id = '$assigned_user_id' AND
               ((
                  (date_start BETWEEN '$db_date_start' AND '$db_date_end') AND
                  date_start != '$db_date_start' AND 
                  date_start != '$db_date_end'
               ) OR (
                  (date_end BETWEEN '$db_date_start' AND '$db_date_end') AND
                  date_end != '$db_date_start' AND
                  date_end != '$db_date_end'
               )) AND
               id != '$record_id' AND
               deleted = 0
         ";
         if ( $GLOBALS['db']->getOne($query) > 0 ) {
            $result = false;
         }
      }
      ob_clean();
      exit(json_encode(array('result' => $result)));
   }

}
