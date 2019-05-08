<?php

class IdeasApi {

   public function getSupervisorName($params) {
      if ( isset($params['id']) ) {
         $user = BeanFactory::getBean('Users', $params['id']);
      } else {
         global $current_user;
         $user = BeanFactory::getBean('Users', $current_user->id);
      }
      $supervisor_id = $user->reports_to_id;
      $supervisor = BeanFactory::getBean('Users', $supervisor_id);

      if ( !empty($supervisor) ) {
         return array(
            'name' => $supervisor->full_name,
            'id' => $supervisor->id,
         );
      }
      return '';
   }

}
