<?php

trait ScheduleReportsLogsTrait {

   public function ACLAccessOverride($view, $return) {
      $view = strtolower($view);
      switch ( $view ) {
         case 'edit':
         case 'popupeditview':
         case 'editview':
         case 'view':
         case 'detail':
         case 'detailview':
         case 'delete':
         case 'export':
         case 'import':
            return false;
      }
      return $return;
   }

}
