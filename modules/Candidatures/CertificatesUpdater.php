<?php

class CertificatesUpdater {

   private $candidate_bean = '';
   private $employee_bean = '';

   const CERTIFICATES_MODULE_NAME = 'Certificates';

   public function __construct($candidate_bean, $employee_bean) {
      $this->candidate_bean = $candidate_bean;
      $this->employee_bean = $employee_bean;
   }

   public function updateCertificate() {
      $certificates_beans = $this->fetchAllCertificatesForCandidate();
      $this->modifyEmployeeRelationship($certificates_beans);
   }

   protected function fetchAllCertificatesForCandidate(): array {
      global $db;
      $fetched_ids = $certificates_beans = [];

      $sql = "SELECT id FROM " . strtolower(static::CERTIFICATES_MODULE_NAME) . " WHERE candidates_id='{$this->candidate_bean->id}'";

      $result = $db->query($sql);

      if ( ( bool ) $result ) {
         while ( $data = $db->fetchByAssoc($result) ) {
            $fetched_ids[] = $data['id'];
         }

         foreach ( $fetched_ids as $id ) {
            $certificates_beans[] = BeanFactory::getBean(static::CERTIFICATES_MODULE_NAME, $id);
         }
      }

      return $certificates_beans;
   }

   protected function modifyEmployeeRelationship(array $certificates_beans) {
      if ( empty($certificates_beans) ) {
         return;
      }

      foreach ( $certificates_beans as $certificate_bean ) {
         $certificate_bean->employee_id = $this->employee_bean->id;
         $certificate_bean->save();
      }
   }

}
