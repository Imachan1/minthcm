<?php

require_once 'include/ViewTools/ViewToolsQueue.php';

class Related {

   /**
    * Method used as GlobalHook
    * @global type $dictionary
    * @param SugarBean $bean
    * @param type $event
    * @param type $arguments
    */
   public function relatedRecalculation(SugarBean &$bean, $event = false, $arguments = false) {
      include('include/ViewTools/Expressions/cache.php');
      if ( isset($related_recalculation[$bean->table_name]) ) {
         require ('cache/Relationships/relationships.cache.php');
         foreach ( array_unique($related_recalculation[$bean->table_name]) as $relationship ) {
            if ( $bean->load_relationship($relationship) ) {
               $related_module_name = $bean->$relationship->getRelatedModuleName();
               $related_fields_names = $this->getCalculatedFieldNamesBasedOnRelatedModule($related_module_name, $relationship);
               if ( $this->shouldChildrenBeResaved($bean, $related_fields_names) ) {
                  $ids = $bean->$relationship->get();
                  foreach ( $ids as $related_record_id ) {
                     $queue = new ViewToolsQueue();
                     $queue->module_name = $related_module_name;
                     $queue->record_id = $related_record_id;
                     $queue->save();
                  }
               }
            }
         }
      }
      return true;
   }

   protected function getCalculatedFieldNamesBasedOnRelatedModule($related_module_name, $relationship) {
      $related_fields = $this->getFieldDefsWhichContainCalculated($related_module_name);
      return $this->getNamesOfFieldsWhichinfluanceOnRelatedRecords($related_fields, $relationship);
   }

   protected function shouldChildrenBeResaved($bean, $related_fields_name) {
      $resave = false;
      foreach ( $related_fields_name as $field_name ) {
         if ( $bean->$field_name != $bean->fetched_row[$field_name] ) {
            $resave = true;
            break;
         }
      }
      return $resave;
   }

   protected function getFieldDefsWhichContainCalculated($module) {
      $focus = BeanFactory::getBean($module);
      $return_fields = [];
      foreach ( $focus->field_defs as $field_name => $def ) {
         if ( isset($def['vt_calculated']) ) {
            $return_fields[$field_name] = $def;
         }
      }
      return $return_fields;
   }

   protected function getNamesOfFieldsWhichinfluanceOnRelatedRecords($fields, $relationship_name) {
      $return_array = [];
      foreach ( $fields as $field_def ) {
         preg_match_all('/related\((.*?)\)/', $field_def['vt_calculated'], $matches);
         foreach ( $matches[1] as $match ) {
            $data = explode(',', $match);
            if ( $data[1] == '#' . $relationship_name ) {
               $return_array[] = substr($data[0], 1);
            }
         }
      }
      return $return_array;
   }

}
