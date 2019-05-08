<?php

class DateFormatter {

   public static function getDateInDatabaseFormat($date) {
      global $timedate, $current_user;

      $user_format = $timedate->get_date_format($current_user);

      $date_time = DateTime::createFromFormat($user_format, $date);

      return $date_time->format('Y-m-d');
   }

}
