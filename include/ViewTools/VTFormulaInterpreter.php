<?php

SugarAutoLoader::requireWithCustom('include/ViewTools/Expressions/VTExpression.php');
SugarAutoLoader::requireWithCustom('include/ViewTools/Expressions/VTFormulaParser.php');

class VTFormulaInterpreter {

   public function calculateFields(SugarBean &$bean) {
      VTExpression::loadBeanValues($bean);
      $moduleFields = VTExpression::getCalculatedFields($bean);
      $ret = '';
      //If calculated definition is set to at least one field, 
      // eval calculated formula and set values
      if ( count($moduleFields) > 0 ) {
         foreach ( $moduleFields as $fieldName => $calculatedParams ) {
            //Eval formula as expression
            if ( isset($calculatedParams) && is_string($calculatedParams) ) {
               $formula = VTFormulaParser::buildFormulaExpression($calculatedParams);
               $ret = VTFormulaParser::evalFormulaExpression($formula);
            }
            //Set calculated value
            $bean->$fieldName = $ret;
         }
      }
      return true;
   }

   public function getToHideFields(SugarBean &$bean) {
      VTExpression::loadBeanValues($bean);
      $moduleFields = VTExpression::getDependencyFields($bean);
      $ret = array();
      if ( count($moduleFields) > 0 ) {
         foreach ( $moduleFields as $fieldName => $calculatedParams ) {
            //Eval formula as expression
            if ( isset($calculatedParams) && is_string($calculatedParams) ) {
               $formula = VTFormulaParser::buildFormulaExpression($calculatedParams);
               if ( !VTFormulaParser::evalFormulaExpression($formula) ) {
                  $ret[$fieldName] = $fieldName;
               }
            }
         }
      }
      return $ret;
   }

   public function validateFields(SugarBean &$bean) {
      VTExpression::loadBeanValues($bean);
      $moduleFields = VTExpression::getValidationFields($bean);
      //If validation definition is set to at least one field, 
      //all fields has to pass positive validation before saving
      $saveAbort = false;
      if ( count($moduleFields) > 0 ) {
         $saveAbort = $this->validateAllVTValidationFields($moduleFields, $bean);
         if ( $saveAbort === true ) {
            $bean->vt_prevent_saving = true;
         }
      }
      return $saveAbort;
   }

   protected function validateAllVTValidationFields($moduleFields, $bean) {
      $saveAbort = false;
      foreach ( $moduleFields as $validationParams ) {
         if ( is_array($validationParams) ) {
            if ( $this->validateFieldAsArray($bean, $validationParams) ) {
               $saveAbort = true;
            }
         } else if ( is_string($validationParams) ) {
            if ( $this->parseValidationAsString($bean, $validationParams) ) {
               $saveAbort = true;
            }
         }
      }
      return $saveAbort;
   }

   protected function validateFieldAsArray($bean, $validationParams) {
      $saveAbort = false;
      foreach ( $validationParams as $validation_string ) {
         if ( $this->parseValidationAsString($bean, $validation_string) ) {
            $saveAbort = true;
         }
      }
      return $saveAbort;
   }

   protected function parseValidationAsString($bean, $validation_string) {
      global $mod_strings, $app_strings, $dictionary;
      $save_abort = false;

      $formula = VTFormulaParser::buildFormulaExpression($validation_string);
      $response = VTFormulaParser::evalFormulaExpression($formula);
      if ( $response === false ) {
         $save_abort = true;
         $field_label = $mod_strings[$dictionary[$bean->object_name]['fields'][$field_name]['vname']];
         if ( $field_label == null ) {
            $field_label = $app_strings[$dictionary[$bean->object_name]['fields'][$field_name]['vname']];
         }
         $formula_parser = new VTFormulaParser();
         $formula_parser->appendErrorMessage(
                 $app_strings['LBL_VIEWTOOLS_FIELDERROR'] . '"' . $field_label . '"', $bean, $field_name
         );
      }
      return $save_abort;
   }

}
