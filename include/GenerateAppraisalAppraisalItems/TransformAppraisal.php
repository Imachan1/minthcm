<?php

SugarAutoLoader::requireWithCustom('include/GenerateAppraisalAppraisalItems/SetAppraisalRelatedModulesRelations.php');
SugarAutoLoader::requireWithCustom('include/GenerateAppraisalAppraisalItems/CreateNewAppraisalItemRecord.php');
SugarAutoLoader::requireWithCustom('include/GenerateAppraisalAppraisalItems/CreateNewAppraisalRecord.php');
SugarAutoLoader::requireWithCustom('include/Notifications/Notification.php');

class TransformAppraisal extends SugarController {

   public $transformed_module_name = '';
   public $transformed_record_id = '';
   public $appraisal_name = '';

   public function __construct($data) {
      $this->transformed_module_name = (isset($data['module'])) ? $data['module'] : null;
      $this->transformed_record_id = (isset($data['record_id'])) ? $data['record_id'] : null;
      $this->appraisal_name = (isset($data['appraisal_name'])) ? $data['appraisal_name'] : null;
   }

   public function transformRecordToAppraisal() {
      $create_apprisal = new CreateNewAppraisalRecord($this->transformed_module_name, $this->appraisal_name);
      $create_apprisal_item = new CreateNewAppraisalItemRecord($this->transformed_module_name);

      $transformed_record_bean = $this->getTransformedBean();
      $appraisal_bean = $create_apprisal->newAppraisal($transformed_record_bean);
      $create_apprisal_item->newAppraisalItem($transformed_record_bean, $appraisal_bean);
   }

   public function runCronJob() {
      $this->transformRecordToAppraisal();
      $this->setNotification();
   }

   protected function getTransformedBean() {
      return BeanFactory::getBean($this->transformed_module_name, $this->transformed_record_id);
   }

   protected function setNotification() {
      global $app_strings, $current_user;
      $notification = new Notification();
      $notification->setAssignedUserId($current_user->id);
      $notification->setDescription(translate($app_strings['LBL_GENERATE_APPRAISAL_APPRAISAL_ITEMS_NOTIFICATION_DESCRIPTION']));
      $notification->disableUniqueValidation();
      $notification->saveAsAlert();
   }

}
