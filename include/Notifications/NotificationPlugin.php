<?php

abstract class NotificationPlugin {

   protected function getNewNotification() {
      return new Notification;
   }

   abstract public function run();
}
