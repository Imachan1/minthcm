<?php

class ReservationsApi {

   CONST RESERVATION_BEAN = 'Reservations';
   CONST RESERVATION_KEY = 'reservation_id';

   public function updateReservation($args) {
      global $timedate;
      $reservation = BeanFactory::newBean(static::RESERVATION_BEAN);
      if ( !empty($args[static::RESERVATION_KEY]) && $reservation->retrieve($args[static::RESERVATION_KEY]) ) {
         $reservation->starting_date = $timedate->to_db($args['starting_date']);
         $reservation->ending_date = $timedate->to_db($args['ending_date']);
         $reservation->save();
         return true;
      }
      return false;
   }

   public function deleteReservation($args) {
      $reservation = BeanFactory::newBean(static::RESERVATION_BEAN);
      if ( !empty($args[static::RESERVATION_KEY]) && $reservation->retrieve($args[static::RESERVATION_KEY]) ) {
         $reservation->mark_deleted($args[static::RESERVATION_KEY]);
         return true;
      }
      return false;
   }

   public function getReservations($args) {
      if ( !empty($args['reservations_ids']) ) {
         $result = array();
         foreach ( $args['reservations_ids'] as $reservation_id ) {
            $reservation = BeanFactory::getBean(static::RESERVATION_BEAN, $reservation_id);
            if ( $reservation && !empty($reservation->id) ) {
               $result[] = new ReservationInfo($reservation);
            }
         }
         return $result;
      }
      return false;
   }

}

class ReservationInfo {

   public $id;
   public $name;
   public $starting_date;
   public $employee;

   public function __construct($bean) {
      $this->id = $bean->id;
      $this->name = $bean->name;
      $this->starting_date = $bean->starting_date;
      $this->employee = $bean->employee_name;
   }

}
