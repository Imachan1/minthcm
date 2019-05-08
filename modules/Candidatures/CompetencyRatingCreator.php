<?php

class CompetencyRatingCreator {

   protected $appraisal_item_module_bean = array();
   protected $competency_bean = '';
   protected $employee_bean = '';

   const COMPETENCY_RATING_MODULE_NAME = 'CompetencyRatings';
   const COMPETENCIES_MODULE_NAME = 'Competencies';
   const EMPLOYEES_MODULE_NAME = 'Employees';

   public function __construct($appraisal_item_bean, $employee_bean) {
      $this->appraisal_item_module_bean = $appraisal_item_bean;
      $this->competency_bean = BeanFactory::getBean(self::COMPETENCIES_MODULE_NAME, $appraisal_item_bean->parent_id);
      $this->employee_bean = $employee_bean;
   }

   public function createOrUpdateRecords() {
      $competency = $this->competency_bean;
      if ( $this->isThereOtherCompetencyRecordWithSameName() ) {
         $competency = $this->getComptenecyBeanWithSameName();
      }

      if ( !$this->wasAppraisalItemConverted($competency) ) {
         $bean = BeanFactory::newBean(self::COMPETENCY_RATING_MODULE_NAME);
         $bean->competency_name = $competency->name;
         $bean->parent_name = $this->employee_bean->first_name . ' ' . $this->employee_bean->last_name;
         $bean->parent_type = self::EMPLOYEES_MODULE_NAME;
         $bean->parent_id = $this->employee_bean->id;
         $bean->rating = $this->appraisal_item_module_bean->value;
         $bean->competency_id = $competency->id;
         $bean->save();
      } else {
         $bean = $this->getConvertedAppraisalItemBean($competency);
         $bean->rating = $this->appraisal_item_module_bean->value;
         $bean->save();
      }
   }

   protected function getConvertedAppraisalItemBean($competency_bean) {
      return BeanFactory::getBean(self::COMPETENCY_RATING_MODULE_NAME, $this->fetchCompetencyRatingIdCreatedFromAppraisalItem($competency_bean));
   }

   protected function wasAppraisalItemConverted($competency_bean) {
      return ( bool ) $this->fetchCompetencyRatingIdCreatedFromAppraisalItem($competency_bean);
   }

   protected function fetchCompetencyRatingIdCreatedFromAppraisalItem($competency_bean) {
      global $db;
      $competency_rating_parent_name = $this->employee_bean->first_name . ' ' . $this->employee_bean->last_name;
      $competency_name = $competency_bean->name;
      $competecy_rating_name = $competency_name . ' - ' . $competency_rating_parent_name;
      $sql = "SELECT id FROM competencyratings WHERE name ='{$competecy_rating_name}' AND competency_id='{$competency_bean->id}' AND deleted=0 ORDER BY date_entered DESC LIMIT 1";
      return $db->getOne($sql);
   }

   protected function isThereOtherCompetencyRecordWithSameName() {
      global $db;
      $sql = "SELECT id FROM competencies WHERE name='{$this->competency_bean->name}' AND  id<>'{$this->competency_bean->id}' AND deleted=0 ORDER BY date_entered ASC LIMIT 1";
      return ( bool ) $db->getOne($sql);
   }

   protected function getComptenecyBeanWithSameName() {
      global $db;
      $sql = "SELECT id FROM competencies WHERE name='{$this->competency_bean->name}' AND id<>'{$this->competency_bean->id}' AND deleted=0 ORDER BY date_entered ASC LIMIT 1";
      $competency_id = $db->getOne($sql);
      return BeanFactory::getBean(self::COMPETENCIES_MODULE_NAME, $competency_id);
   }

}
