<?php

class AppraisalsApi {
   
   public function getAppraisalType($appraisal_id) {
      if( is_array($appraisal_id)) {
         $appraisal_id = $appraisal_id['appraisal_id'];
      }
      $appraisal = BeanFactory::getBean('Appraisals', $appraisal_id);
      if ( !empty($appraisal)) {
         return $appraisal->type;
      }
      return false;
   }
   
}

