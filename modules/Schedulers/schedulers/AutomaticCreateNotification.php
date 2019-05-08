<?php

$job_strings[] = 'AutomaticCreateNotification';

function AutomaticCreateNotification() {
   require_once 'include/Notifications/NotificationManager.php';
   $notification_manager = new NotificationManager;
   $notification_manager->run();
   return true;
}
