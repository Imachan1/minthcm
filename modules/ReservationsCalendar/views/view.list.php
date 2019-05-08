<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

require_once('include/MVC/View/views/view.list.php');

class ReservationsCalendarViewList extends ViewList {

   public function display() {
      $resources = $this->getResources();
      $this->ss->assign('resources', $resources);
      $calendars = $this->getCalendarList($resources);
      $this->ss->assign('calendars', json_encode($calendars));
      $reservations = $this->getReservations($calendars);
      $this->ss->assign('reservations', json_encode($reservations));
      $resource_id = filter_input(INPUT_GET, 'resource_id', FILTER_SANITIZE_STRING);
      if ( !empty($resource_id) ) {
         $this->ss->assign('default_resource', $resource_id);
      }
      echo $this->ss->fetch('modules/ReservationsCalendar/tpl/view.list.tpl');
   }

   protected function getResources() {
      $resource = BeanFactory::getBean('Resources');
      $resources = $resource->get_full_list("name", "type='for_reservation'");
      return (!empty($resources)) ? $resources : array();
   }

   protected function getCalendarList($resources) {
      $calendars = array();
      foreach ( $resources as $resource ) {
         $calendar = array();
         $calendar['id'] = $resource->id;
         $calendar['name'] = $resource->name;
         $calendar['checked'] = false;
         $calendar['color'] = '#000000';
         $calendar['bgColor'] = '#009976';
         $calendar['borderColor'] = '#3A87AD';
         $calendars[] = $calendar;
      }
      return $calendars;
   }

   protected function getReservations($calendars) {
      $reservations = array();
      $reservation = BeanFactory::getBean('Reservations');
      foreach ( $calendars as $calendar ) {
         $reservation_beans = $reservation->get_full_list("name", "resource_id='{$calendar['id']}' AND ending_date > (NOW() - INTERVAL 3 MONTH)");
         if ( !empty($reservation_beans) ) {
            foreach ( $reservation_beans as $bean ) {
               $row = array();
               $row['id'] = $bean->id;
               $row['calendarId'] = $bean->resource_id;
               $row['title'] = $bean->name;
               $row['category'] = "time";
               $row['start'] = $this->getDate($bean->starting_date);
               $row['end'] = $this->getDate($bean->ending_date);
               $row['attendees'] = [ 'anyone' ];
               $row['raw'] = [ 'creator' => [ 'name' => $bean->employee_name ] ];
               $row['isReadOnly'] = !($bean->ACLAccess('edit')); 
               $row['isNotDeletable'] = !($bean->ACLAccess('delete'));
               $row['detailViewAccess'] = $bean->ACLAccess('detail');
               $reservations[] = $row;
            }
         }
      }
      return $reservations;
   }

   protected function getDate($date) {
      global $current_user, $timedate;
      $tz = $current_user->getPreference('timezone');
      $datef = $current_user->getPreference('datef');
      $timef = $current_user->getPreference('timef');
      $format = $datef . " " . $timef;
      if ( empty($tz) ) {
         $tz = 'UTC';
      }
      $tzo = new DateTimeZone($tz);
      $result_date = $timedate->asUser($timedate->fromDb($date), $current_user);
      $result_date = DateTime::createFromFormat($format, $result_date, $tzo);
      return $result_date->format('c');
   }

}
