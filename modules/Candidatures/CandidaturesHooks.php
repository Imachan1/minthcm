<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

class CandidaturesHooks {

   public function count_employees_number($bean, $event, $arguments) {
      $this->count_employees($bean->recruitment_id);
      $this->count_employees($bean->fetched_row['recruitment_id']);
   }

   private function count_employees($recruitment_id) {
      if ( $recruitment_id != '' ) {
         $recruitment = BeanFactory::getBean('Recruitments', $recruitment_id);
         if ( $recruitment->load_relationship('candidatures') ) {
            $employees_number = 0;
            $candidatures = $recruitment->candidatures->getBeans();
            foreach ( $candidatures as $candidature ) {
               if ( $candidature->status == 'Hired' ) {
                  $employees_number++;
               }
            }
            if ( $employees_number != $recruitment->employees_number ) {
               $recruitment->employees_number = $employees_number;
               $recruitment->save();
            }
         }
      }
   }

}
