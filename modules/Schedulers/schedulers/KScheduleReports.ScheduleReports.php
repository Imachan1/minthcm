<?php

$job_strings[] = 'send_reports';

function send_reports() {
   require_once 'modules/ScheduleReports/Utils.php';

   global $timedate, $db;
   $current_time = $timedate->nowDbDate();

   $sql = "
      SELECT
         id
      FROM
         schedulereports
      WHERE
         (
            (
               date_send <= DATE_ADD(
                  '{$current_time}',
                  INTERVAL -1 DAY
              ) 
              AND frequency_performance = 'every_day'
            ) OR (
               date_send <= DATE_ADD(
                  '{$current_time}',
                  INTERVAL -7 DAY
              ) 
              AND frequency_performance = 'every_week'
            ) OR (
               date_send <= DATE_ADD(
                  '{$current_time}',
                  INTERVAL -30 DAY
              )
              AND frequency_performance = 'every_month'
            )
         )
         AND deleted = '0'
         AND active = '1'
   ";
   $result = $db->query($sql);

   $generator = new GeneratorEmails();
   while ( $row = $db->fetchByAssoc($result) ) {
      $schedulereports_bean = BeanFactory::getBean("ScheduleReports", $row['id']);
      $generator->generatePDF($schedulereports_bean);
   }

   return true;
}

?>
