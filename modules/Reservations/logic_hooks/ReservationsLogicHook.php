<?php

class ReservationsLogicHook {

   protected $supported_modules = [ 'Calls', 'Meetings' ];
   protected $resources_rel = "resources";
   protected $reservations_rel = "reservations";

   public function beforeActivityDelete($bean) {
      if ( in_array($bean->module_name, array_keys($this->supported_modules)) ) {
         $rel = $this->reservations_rel;
         if ( $bean->load_relationship($rel) ) {
            foreach ( $bean->$rel->get() as $reservation_id ) {
               $reservation = BeanFactory::newBean('Reservations');
               $reservation->mark_deleted($reservation_id);
            }
         }
      }
   }

   public function afterActivitySave($bean) {
      if ( in_array($bean->module_name, array_keys($this->supported_modules)) ) {
         $rel = $this->resources_rel;
         if ( $bean->load_relationship($rel) ) {
            $resources_ids = $bean->$rel->get();
            $this->createReservations($bean, array_diff($bean->resources_arr, $resources_ids));
            if ( $bean->date_start != $bean->fetched_row['date_start'] ||
                    $bean->date_end != $bean->fetched_row['date_end'] ||
                    $bean->duration_hours != $bean->fetched_row['duration_hours'] ||
                    $bean->duration_minutes != $bean->fetched_row['duration_minutes']
            ) {
               $this->updateReservations($bean);
            }
         }
      }
   }

   private function createReservations($bean, $resources_ids) {
      global $current_user;
      foreach ( $resources_ids as $resource_id ) {
         $reservation = BeanFactory::newBean('Reservations');
         $reservation->name = $bean->name;
         $reservation->resource_id = $resource_id;
         $reservation->starting_date = $bean->date_start;
         if ( $bean->module_name == "Calls" ) {
            $date_end = new DateTime($bean->date_start);
            $date_end->modify("+" . $bean->duration_hours . " hours +" . $bean->duration_minutes . " minutes");
            $reservation->ending_date = $date_end->format('Y-m-d H:i:s');
         } else {
            $reservation->ending_date = $bean->date_end;
         }
         $reservation->parent_type = $bean->module_name;
         $reservation->parent_id = $bean->id;
         $reservation->assigned_user_id = $current_user->id;
         $reservation->employee_id = $current_user->id;
         $reservation->save();
      }
   }

   private function updateReservations($bean) {
      $rel = $this->reservations_rel;
      if ( $bean->load_relationship($rel) ) {
         foreach ( $bean->$rel->get() as $reservation_id ) {
            $reservation = BeanFactory::getBean('Reservations', $reservation_id);
            if ( $reservation && !empty($reservation->id) ) {
               $reservation->starting_date = $bean->date_start;
               if ( $bean->module_name == "Calls" ) {
                  $date_end = new DateTime($bean->date_start);
                  $date_end->modify("+" . $bean->duration_hours . " hours +" . $bean->duration_minutes . " minutes");
                  $reservation->ending_date = $date_end->format('Y-m-d H:i:s');
               } else {
                  $reservation->ending_date = $bean->date_end;
               }
               $reservation->save();
            }
         }
      }
   }

}
