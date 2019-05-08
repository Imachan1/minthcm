<?php

require_once 'include/Notifications/Notification.php';
require_once 'include/Notifications/NotificationPlugin.php';

class NotificationManager {

   const PLUGINS_DIRECTORY = "./include/Notifications/plugins";

   protected $plugins_collection = array();

   public function __construct() {
      $files = scandir(self::PLUGINS_DIRECTORY);

      foreach ( $files as $file ) {
         if ( $file == '.' || $file == '..' ) {
            continue;
         }

         include self::PLUGINS_DIRECTORY . '/' . $file;
         $class_name = self::getClassFromFile($file);
         $plugin = new $class_name;
         if ( $plugin instanceof NotificationPlugin ) {
            $this->plugins_collection[] = $class_name;
         }
      }
   }

   public function run() {
      self::setInactiveNotifications();
      foreach ( $this->plugins_collection as $plugin_class ) {
         $plugin = new $plugin_class();
         $plugin->run();
      }
   }

   protected static function setInactiveNotifications() {
      $GLOBALS['db']->query("UPDATE `alerts` SET `is_read`=1 WHERE `alert_type`='custom';");
   }

   protected static function getClassFromFile($file) {
      $r = substr($file, 0, -4);
      return $r;
   }

   public static function isValidUser($user_id) {
      $user_bean = BeanFactory::getBean('Users', $user_id);
      return (!empty($user_bean->id));
   }

   public static function getUserFullName($user_id) {
      $user_bean = BeanFactory::getBean('Users', $user_id);
      if ( !empty($user_bean->id) ) {
         return $user_bean->full_name;
      }
      return false;
   }

   public static function toDbDatetime($input_string) {
      global $timedate;
      $output_string = "";
      $date_db_format = "/^\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}:\d{2}$/";

      if ( preg_match($date_db_format, $input_string) ) {
         $output_string = $input_string;
      } else {
         $start_arr = explode(' ', $input_string);
         $start_time = $start_arr[1];
         $start_date = $timedate->to_db_date($input_string, false);
         if ( $start_date ) {
            $output_string = $start_date . ' ' . $start_time;
         }
      }

      return $output_string;
   }

   public static function getWrongNotifications() {
      global $db;
      $sql = "SELECT
              `alert`.`id` AS id
            FROM
              `alerts` AS alert
            LEFT JOIN
              `workschedules` AS work_schedule
                ON `alert`.`parent_type`='WorkSchedules'
                AND `alert`.`parent_id`=`work_schedule`.`id`
                AND `work_schedule`.`deleted`=0
            WHERE
              `alert`.`deleted`=0
              AND    (
                `work_schedule`.`id` IS NULL
                OR `alert`.`assigned_user_id` <> `work_schedule`.`assigned_user_id`
              );";
      $sql_result = $db->query($sql);
      $result = array();
      while ( $result[] = $db->fetchByAssoc($sql_result) );
      return $result;
   }

}
