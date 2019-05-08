<?php

SugarAutoLoader::requireWithCustom('modules/Candidatures/CompetencyRatingCreator.php');
SugarAutoLoader::requireWithCustom('modules/Candidatures/EmployeeCreator.php');
SugarAutoLoader::requireWithCustom('modules/Candidatures/AppraisalsLoader.php');
SugarAutoLoader::requireWithCustom('modules/Candidatures/CertificatesUpdater.php');

class CandidatureConverter {

   const CANDIDATES_MODULE_NAME = 'Candidates';
   const POSITIONS_MODULE_NAME = 'Positions';
   const RECRUITEMENT_MODULE_NAME = 'Recruitments';
   const CANDIDATURE_MODLE_NAME = 'Candidatures';
   const APPRAISAL_ITEMS_MODULE_NAME = 'AppraisalItems';
   const APPRAISAL_MODULE_NAME = 'Appraisals';
   const COMPETENCIES_MODULE_NAME = 'Competencies';

   protected $converted_candidature_record_id = '';
   protected $converted_candidature_bean = '';
   protected $latest_appraisal_bean = '';
   protected $latest_appraisal_items_beans = array();

   public function __construct($record_id) {
      $this->converted_candidature_record_id = $record_id;
   }

   public function convert() {
      $beans_for_employee = $this->setModulesBeans();

      $create_employee = new EmployeeCreator($beans_for_employee);
      $employee = $create_employee->createOrUpdate();

      if ( !is_null($this->latest_appraisal_bean) && !empty($this->latest_appraisal_items_beans) && !$employee['candidate_employee_relation'] ) {

         foreach ( $this->latest_appraisal_items_beans as $one_appraisal_item_bean ) {
            if ( $one_appraisal_item_bean->parent_type == self::COMPETENCIES_MODULE_NAME ) {

               $create_competency_rating = new CompetencyRatingCreator($one_appraisal_item_bean, $employee['employee_bean']);
               $create_competency_rating->createOrUpdateRecords();
            }
         }
      }

      $certificate_updater = new CertificatesUpdater($beans_for_employee[static::CANDIDATES_MODULE_NAME], $employee['employee_bean']);
      $certificate_updater->updateCertificate();

      return $employee['employee_bean']->id;
   }

   protected function setModulesBeans() {
      $appraisals = new AppraisalsLoader();

      $this->converted_candidature_bean = $this->getConvertedCandidatureBean();
      $this->latest_appraisal_bean = $appraisals->getLatestAppraisalBean($this->converted_candidature_bean);
      $this->latest_appraisal_items_beans = $appraisals->getLatestAppraisalItemsBeans($this->latest_appraisal_bean);

      $candidate_bean = $this->getCandidateBean();
      $position_bean = $this->getPositionBean();

      return array(
         self::POSITIONS_MODULE_NAME => $position_bean,
         self::CANDIDATES_MODULE_NAME => $candidate_bean,
         self::APPRAISAL_MODULE_NAME => $this->latest_appraisal_bean,
         self::APPRAISAL_ITEMS_MODULE_NAME => $this->latest_appraisal_items_beans,
      );
   }

   protected function getConvertedCandidatureBean(): Candidatures {
      return BeanFactory::getBean(self::CANDIDATURE_MODLE_NAME, $this->converted_candidature_record_id);
   }

   protected function getPositionBean(): Positions {
      global $db;

      $recruitement_id = (empty($this->converted_candidature_bean->recruitment_end_id) ? $this->converted_candidature_bean->recruitment_id : $this->converted_candidature_bean->recruitment_end_id);
      $sql = "SELECT position_id FROM recruitments WHERE id='{$recruitement_id}' AND deleted=0";
      $result_position_id = $db->getOne($sql);
      return BeanFactory::getBean(self::POSITIONS_MODULE_NAME, $result_position_id);
   }

   protected function getCandidateBean(): Candidates {
      global $db;
      $sql = "SELECT candidate_id FROM candidatures WHERE id='{$this->converted_candidature_bean->id}'";
      $result_candidate_id = $db->getOne($sql);

      return BeanFactory::getBean(self::CANDIDATES_MODULE_NAME, $result_candidate_id);
   }

}
