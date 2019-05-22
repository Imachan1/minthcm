<?php

require_once('modules/Ideas/SugarFeeds/IdeasFeed.php');

class Ideas extends Basic {

   public $new_schema = true;
   public $module_dir = 'Ideas';
   public $object_name = 'Ideas';
   public $table_name = 'ideas';
   public $importable = true;
   public $id;
   public $name;
   public $date_entered;
   public $date_modified;
   public $modified_user_id;
   public $modified_by_name;
   public $created_by;
   public $created_by_name;
   public $description;
   public $deleted;
   public $created_by_link;
   public $modified_user_link;
   public $assigned_user_id;
   public $assigned_user_name;
   public $user_name;
   public $user_id;
   public $assigned_user_link;
   public $SecurityGroups;
   public $status;
   public $explanation;

   public function bean_implements($interface) {
      $result = false;
      if ( $interface === 'ACL' ) {
         $result = true;
      }
      return $result;
   }

   protected function postSave() {
      $if = new IdeasFeed();
      $if->pushFeed($this, null, null);
      if ( !empty($this->user_id) && $this->user_id != $this->fetched_row['user_id'] ) {
         $this->addDecisionMakerPrivateGroup();
         $this->addDecisionMakerNotification();
      }
   }

   protected function addDecisionMakerPrivateGroup() {
      $user = BeanFactory::getBean('Users', $this->user_id);
      if ( $user && !empty($user->id) && $this->load_relationship('SecurityGroups') ) {
         $group_id = $user->getUserPrivateGroup();
         if ( $group_id ) {
            $this->SecurityGroups->add($group_id);
         }
      }
   }

   protected function addDecisionMakerNotification() {
      global $app_strings, $current_user;

      if ( $this->user_id !== $current_user->id ) {
         SugarAutoLoader::requireWithCustom('include/Notifications/Notification.php');
         $notification = new Notification();
         $notification->setAssignedUserId($this->user_id)
            ->setDescription($app_strings['LBL_DECISION_MAKER_ASSIGNMENT_FOR_IDEAS'])
            ->setRelatedBean($this->id, 'Ideas')
            ->saveAsAlert();
         $notification->disableUniqueValidation();
      }
   }

}
