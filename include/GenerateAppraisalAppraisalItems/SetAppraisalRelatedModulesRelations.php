<?php

class SetAppraisalRelatedModulesRelations {

   public function setRelatedModulesRelations($roles_beans, $appraisal_bean) {
      $this->setAppraisalRoleRelations($roles_beans, $appraisal_bean);
   }

   protected function setAppraisalRoleRelations($roles_beans, $appraisal_bean) {
      foreach ( $roles_beans as $one_role_bean ) {
         $one_role_bean->load_relationship('appraisals');
         $one_role_bean->appraisals->add($appraisal_bean);
      }
   }

}
