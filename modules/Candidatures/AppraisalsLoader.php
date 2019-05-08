<?php

class AppraisalsLoader {

   const APPRAISAL_ITEMS_MODULE_NAME = 'AppraisalItems';
   const APPRAISAL_MODULE_NAME = 'Appraisals';

   public function getLatestAppraisalBean($candidature_bean) {
      global $db;

      $sql = "SELECT id FROM appraisals WHERE candidature_id='{$candidature_bean->id}' AND deleted=0 ORDER BY date_entered DESC LIMIT 1";
      $result_bean_id = $db->getOne($sql);

      $appraisals_related_to_converted_candidature = BeanFactory::getBean(self::APPRAISAL_MODULE_NAME, $result_bean_id);

      if ( empty($appraisals_related_to_converted_candidature) ) {
         return NULL;
      }

      return $appraisals_related_to_converted_candidature;
   }

   public function getLatestAppraisalItemsBeans($latest_appraisal_bean) {
      global $db;
      $fetched_appraisal_items_beans = array();

      if ( !is_null($latest_appraisal_bean) ) {
         $sql = "SELECT id FROM appraisalitems WHERE appraisal_id='{$latest_appraisal_bean->id}' AND deleted=0";
         $result = $db->query($sql);

         while ( $row = $db->fetchByAssoc($result) ) {
            array_push($fetched_appraisal_items_beans, BeanFactory::getBean(self::APPRAISAL_ITEMS_MODULE_NAME, $row['id']));
         }
      }

      return $fetched_appraisal_items_beans;
   }

}
