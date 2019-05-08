<?php

class ResourcesApi {

   public function isReservable($args) {

      global $db;
      $fetched_rows = '';

      $sql = "SELECT type FROM resources WHERE id='{$args['resource_id']}' AND deleted=0";
      $result = $db->query($sql);

      while ( $row = $db->fetchByAssoc($result) ) {
         $fetched_rows = $row['type'];
      }

      return ($fetched_rows == 'not_for_reservation' ? false : true);
   }

   public function getBusyTimeSlots($args) {
      $busy_timeslots = array();
      if ( isset($args['resource_id']) && !empty($args['timeslots']) ) {
         $start_date = $this->getDateFromHash(current($args['timeslots'])['hash']);
         $end_date = $this->getDateFromHash(end($args['timeslots'])['hash']);
         $reservations = $this->getReservations($args['resource_id'], $start_date, $end_date);
         $reservations_timeslots = $this->getReservationsTimeSlots($reservations);
         foreach ( $args['timeslots'] as $timeslot ) {
            $this->getBusyTimeSlotsPerSlot($timeslot, $reservations_timeslots, $busy_timeslots);
         }
      }
      return $busy_timeslots;
   }

   public function getBusyTimeSlotsPerSlot($timeslot, $reservations_timeslots, &$busy_timeslots) {
      foreach ( $reservations_timeslots as $reservation_id => $reservation_timeslots ) {
         if ( in_array($this->getDateFromHash($timeslot['hash']), $reservation_timeslots) ) {
            if ( !isset($busy_timeslots[$timeslot['hash']]) ) {
               $busy_timeslots[$timeslot['hash']] = ['records' => [$reservation_id => 'Reservations']];
            } else {
               $busy_timeslots[$timeslot['hash']]['records'][$reservation_id] = 'Reservations';
            }
         }
      }
   }

   private function getDateFromHash($hash) {
      $date_array = str_split($hash, 2);
      $date = $date_array[0] .
         $date_array[1] . '-' .
         $date_array[2] . '-' .
         $date_array[3] . ' ' .
         $date_array[4] . ':' .
         $date_array[5];
      return date('Y-m-d H:i', strtotime($date . " +1 month"));
   }

   private function getReservationsTimeSlots($reservations) {
      $reservations_timeslots = array();
      foreach ( $reservations as $reservation ) {
         $startTime = new DateTime($reservation['starting_date']);
         $endTime = new DateTime($reservation['ending_date']);
         $timeArray = array();
         while ( $startTime < $endTime ) {
            $timeArray[] = $startTime->format('Y-m-d H:i');
            $startTime->modify('+15 minutes');
         }
         $reservations_timeslots[$reservation['id']] = $timeArray;
      }
      return $reservations_timeslots;
   }

   private function getReservations($resource_id, $start_date, $end_date) {
      global $db;
      $resevations = array();
      $sql = "SELECT id, starting_date, ending_date "
         . "FROM reservations "
         . "WHERE resource_id='{$resource_id}' "
         . "AND starting_date < '{$end_date}' "
         . "AND ending_date > '{$start_date}' "
         . "AND deleted=0";
      $result = $db->query($sql);
      while ( $row = $db->fetchByAssoc($result) ) {
         $resevations[] = $row;
      }
      return $resevations;
   }

}
