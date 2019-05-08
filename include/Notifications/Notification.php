<?php

require_once 'include/Notifications/NotificationManager.php';

class Notification {

   protected $name;
   protected $description;
   protected $assigned_user_id;
   protected $related_bean_type;
   protected $related_bean_id;
   
   protected $skip_uniq_validate = FALSE;

   public function isUnique() {
      $alert_id = $GLOBALS['db']->getOne($this->buildUniqueQueryChecker());
      return (empty($alert_id));
   }
   
   public function disableUniqueValidation(){
      $this->skip_uniq_validate = TRUE;
   }

   public function setActive() {
      global $db;
      $db->query("UPDATE `alerts` SET `is_read`=0 " . $this->buildUniqueQueryCheckerWhere());
   }

   protected function buildUniqueQueryChecker() {
      return " SELECT id FROM `alerts` " . $this->buildUniqueQueryCheckerWhere();
   }

   protected function buildUniqueQueryCheckerWhere() {
      return " WHERE `deleted` = 0
         AND `parent_type` = '{$this->related_bean_type}'
         AND `parent_id` = '{$this->related_bean_id}'
         AND `assigned_user_id` = '{$this->assigned_user_id}'
         AND `alert_type` = 'custom'";
   }

   public function setRelatedBean($related_bean_id, $related_bean_type) {
      $this->related_bean_type = $related_bean_type;
      $this->related_bean_id = $related_bean_id;
      return $this;
   }

   public function setAssignedUserId($assigned_user_id) {
      if ( !NotificationManager::isValidUser($assigned_user_id) ) {
         return false;
      }
      $this->assigned_user_id = $assigned_user_id;
      return $this;
   }

   public function setName($name) {
      $this->name = $name;
      return $this;
   }

   public function setDescription($description) {
      $this->description = $description;
      return $this;
   }

   public function saveAsAlert() {
      if ( $this->skip_uniq_validate || $this->isUnique() ) {
         $bean = BeanFactory::newBean('Alerts');
         $bean->name = $bean->date_entered ? $bean->date_entered : date("Y-m-d") . ' ' . NotificationManager::getUserFullName($this->assigned_user_id);
         $bean->description = $this->description;
         $bean->parent_type = $this->related_bean_type;
         $bean->parent_id = $this->related_bean_id;
         $bean->assigned_user_id = $this->assigned_user_id;
         $bean->is_read = 0;
         $bean->alert_type = 'custom';

         if ( !empty($bean->parent_id) ) {
            $bean->url_redirect = 'index.php?module=' . $bean->parent_type . '&action=DetailView&record=' . $bean->parent_id;
         } else {
            $bean->url_redirect = 'index.php?module=' . $bean->parent_type;
         }

         $bean->save();
      } else {
         $this->setActive();
      }
      return $this;
   }

}
