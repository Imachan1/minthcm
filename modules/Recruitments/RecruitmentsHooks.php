<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

class RecruitmentsHooks {

   public function count_employees_number($bean, $event, $arguments) {
      if ( $arguments['relationship'] == 'candidatures_recruitments' && $bean->load_relationship('candidatures') ) {
         $employees_number = 0;
         $candidatures = $bean->candidatures->getBeans();
         foreach ( $candidatures as $candidature ) {
            if ( $candidature->status == 'Hired' ) {
               $employees_number++;
            }
         }
         if ( $employees_number != $bean->employees_number ) {
            $bean->save();
         }
      }
   }

}
