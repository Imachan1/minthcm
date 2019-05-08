<?php

class ForceDashboardRelationships {

   public function clearUserRelationshipsWithDM($bean, $event_name, $arguments) {
      if ( $arguments['related_module'] === 'DashboardManager' ) {
         $bean->forced_tabs_dashboard_id = '';
         $bean->locked_dashboard_id = '';
         $bean->one_time_default_dashboard_id = '';
      }
   }

}
