<?php

class CandidaturesLogicHook {

   protected $rel_map = array(
      'Calls' => 'candidates',
      'Meetings' => 'candidates',
   );

   public function afterActivitySave($bean) {
      if ( in_array($bean->module_name, array_keys($this->rel_map)) ) {
         $rel = $this->rel_map[$bean->module_name];

         if ( $bean->fetched_row['parent_id'] !== $bean->parent_id && $bean->fetched_row['parent_type'] === 'Candidatures' ) {
            $candidature = BeanFactory::getBean('Candidatures', $bean->fetched_row['parent_id']);
            if ( $candidature && $candidature->id && $bean->load_relationship($rel) ) {
               $bean->$rel->delete($candidature->candidate_id);
            }
         }

         if ( $bean->fetched_row['parent_id'] !== $bean->parent_id && $bean->parent_type === 'Candidatures' ) {
            $candidature = BeanFactory::getBean('Candidatures', $bean->parent_id);
            if ( $candidature && $candidature->id && $bean->load_relationship($rel) ) {
               $bean->$rel->add($candidature->candidate_id);
            }
         }
      }
   }

}
