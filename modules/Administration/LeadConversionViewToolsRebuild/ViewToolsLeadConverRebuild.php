<?php

class ViewToolsLeadConverRebuild {

   protected $modules_used_in_lead_conversion = array();
   protected $initArray;

   public function __construct() {
      $path = 'modules/Leads/metadata/convertdefs.php';
      if ( file_exists('custom/' . $path) ) {
         include 'custom/' . $path;
      } else {
         include $path;
      }
      foreach ( $viewdefs as $module_name => $def ) {
         $this->modules_used_in_lead_conversion[] = $module_name;
      }
   }

   public function addLeadConversionDependency($initArray) {
      $this->initArray = $initArray;
      foreach ( $this->modules_used_in_lead_conversion as $module_used_in_conversion ) {
         if ( isset($this->initArray[strtolower($module_used_in_conversion)]) ) {
            $this->addModuleToLeadConversion($module_used_in_conversion);
         }
      }
      return $this->initArray;
   }

   protected function addModuleToLeadConversion($module) {
      foreach ( $this->initArray[strtolower($module)] as $field_name => $target_fields ) {
         $this->addFieldToModuleInLeadConversion($module, $field_name, $target_fields);
      }
   }

   protected function addFieldToModuleInLeadConversion($module, $field_name, $target_fields) {
      foreach ( $target_fields as $key => $value ) {
         $this->initArray['leads'][$module . $field_name][$module . $key] = $module . $value;
      }
   }

   public function addLeadConversionRequirements($initArray) {
      $this->initArray = $initArray;
      foreach ( $this->modules_used_in_lead_conversion as $module_used_in_conversion ) {
         if ( isset($this->initArray[strtolower($module_used_in_conversion)]) ) {
            $this->addModuleToLeadConversionRequirements($module_used_in_conversion);
         }
      }
      return $this->initArray;
   }

   protected function addModuleToLeadConversionRequirements($module) {
      foreach ( $this->initArray[strtolower($module)] as $field_name => $requirement_option ) {
         $this->initArray['leads'][$module . $field_name] = $requirement_option;
      }
   }

}
