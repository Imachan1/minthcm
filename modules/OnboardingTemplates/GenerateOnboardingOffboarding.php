<?php

if ( !defined('sugarEntry') ) {
   define('sugarEntry', true);
}

SugarAutoLoader::requireWithCustom('include/Notifications/Notification.php');

class GenerateOnboardingOffboarding {

   protected $module_name;
   protected $template_id;
   protected $employee_id;
   protected $date_start;
   protected $record_id;
   protected $process;
   protected $user_scheduled_onboarding;

   public function __construct($data) {
      $this->module_name = (isset($data['module_name'])) ? $data['module_name'] : null;
      $this->template_id = (isset($data['template_id'])) ? $data['template_id'] : null;
      $this->employee_id = (isset($data['employee_id'])) ? $data['employee_id'] : null;
      $this->date_start = (isset($data['date_start'])) ? $data['date_start'] : null;
      $this->record_id = (isset($data['record_id'])) ? $data['record_id'] : null;
      if ( isset($data['user_scheduled_onboarding_id']) ) {
         $this->user_scheduled_onboarding = BeanFactory::getBean('Users', $data['user_scheduled_onboarding_id']);
      } else {
         $this->user_scheduled_onboarding = BeanFactory::newBean('Users');
   }
   }

   public function generate() {
      $template = BeanFactory::getBean($this->module_name, $this->template_id);
      if ( $this->module_name == 'OnboardingTemplates' ) {
         $this->createProcess('Onboardings', 'onboardingtemplate_id', $template->assigned_user_id);
      } else {
         $this->createProcess('Offboardings', 'offboardingtemplate_id', $template->assigned_user_id);
      }
      if ( $template->load_relationship('elements') ) {
         foreach ( $template->elements->getBeans() as $element ) {
            $this->createRecordOfElementType($element);
         }
      }
      $this->addNotification();
   }

   protected function createProcess($process_name, $relate_id_field_name, $assigned_user_id) {
      $bean = BeanFactory::newBean($process_name);
      $bean->date_start = $this->date_start;
      $bean->employee_id = $this->employee_id;
      $bean->$relate_id_field_name = $this->template_id;
      $bean->assigned_user_id = $assigned_user_id;
      $bean->save();
      $this->addSecurityGroupToRecord($bean, $this->user_scheduled_onboarding->getUserPrivateGroup());
      $this->process = $bean;
   }

   protected function addSecurityGroupToRecord($bean, $sg_id) {
      $sg_relation_name = 'SecurityGroups';
      if ( $bean->load_relationship($sg_relation_name) ) {
         return $bean->$sg_relation_name->add($sg_id);
      } else {
         $GLOBALS['log']->fatal("Unable to load relationship {$sg_relation_name} for {$bean->object_name}");
         return false;
      }
   }

   protected function createRecordOfElementType($element) {
      switch ( $element->type ) {
         case 'task':
            $bean = $this->createTask($element);
            break;
         case 'training':
            $bean = $this->createTraining($element);
            break;
         case 'exit_interview':
            $bean = $this->createExitInterview($element);
            break;
         default:
            return false;
      }
      return $this->addSecurityGroupToRecord($bean, $this->user_scheduled_onboarding->getUserPrivateGroup());
   }

   protected function createTask($element) {
      global $timedate;
      $bean = BeanFactory::newBean('Tasks');
      $bean->name = $element->name;
      $bean->assigned_user_id = (( bool ) $element->own_task ? $this->employee_id : $element->users_id);
      $date_start_object = new DateTime($this->date_start);
      $date_start_object->modify("+" . $element->days_from_start . " days");
      $bean->date_start = $date_start_object->format($timedate->get_db_date_time_format());
      $date_due = $date_start_object->modify("+" . $element->task_duration . " hours");
      $bean->date_due = $date_due->format($timedate->get_db_date_time_format());
      $bean->parent_type = $this->process->module_name;
      $bean->parent_id = $this->process->id;
      $bean->save();
      return $bean;
   }

   protected function createTraining($element) {
      global $timedate;
      $bean = BeanFactory::newBean('Trainings');
      $bean->name = $element->name;
      $bean->assigned_user_id = $element->users_id;
      $date_start_object = new DateTime($this->date_start);
      $date_start_object->modify("+" . $element->days_from_start . " days");
      $bean->date_start = $date_start_object->format($timedate->get_db_date_time_format());
      $date_end = $date_start_object->modify("+" . $element->task_duration . " hours");
      $bean->date_end = $date_end->format($timedate->get_db_date_time_format());
      $bean->training_type = "internal";
      $bean->parent_type = $this->process->module_name;
      $bean->parent_id = $this->process->id;
      $bean->save();
      return $bean;
   }

   protected function createExitInterview($element) {
      global $timedate;
      $bean = BeanFactory::newBean('ExitInterviews');
      $bean->name = $element->name;
      $bean->assigned_user_id = $element->users_id;
      $bean->employee_id = $this->employee_id;
      $date_start_object = new DateTime($this->date_start);
      $date_start_object->modify("+" . $element->days_from_start . " days");
      $bean->date_start = $date_start_object->format($timedate->get_db_date_time_format());
      $date_end = $date_start_object->modify("+" . $element->task_duration . " hours");
      $bean->date_end = $date_end->format($timedate->get_db_date_time_format());
      $bean->save();
      return $bean;
   }

   protected function addNotification() {
      global $app_strings, $current_user;
      $notification = new Notification();
      $notification->setAssignedUserId($current_user->id);
      $notification->setDescription(translate($app_strings['LBL_GENERATE_ONBOARDING_OFFBOARDING']));
      $notification->disableUniqueValidation();
      $notification->saveAsAlert();
   }

}
