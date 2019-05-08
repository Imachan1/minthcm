<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

class PDFController {

   protected $template_id;
   protected $root_ids = array();
   protected $module_name;
   protected $mode;
   protected $zip_path = PDFGENERATOR_PATH_TEMP;
   /**
    *
    * @var DBManager db 
    */
   protected $db;

   public function __construct($template_id, $module_name, $root_ids, $mode = 'FILE', $filename_regex = null) {
      $this->template_id = $template_id;
      $this->module_name = $module_name;
      $this->root_ids = $root_ids;
      $this->mode = $mode;
      $this->filename_regex = $filename_regex;
      $this->db = DBManagerFactory::getInstance();
   }

   public function process($generator_name = "PDF", $params = null) {
      if ( $errors = $this->checkParams($generator_name, $params) ) {
         $GLOBALS['log']->fatal(print_r($errors, true));
      } else {
         $generator = $this->getPDFGenerator($generator_name, $params);
         switch ( $this->mode ) {
            case 'FILE':
               $generator->Output($this->root_ids, $this->filename_regex, 'I');
               break;
            case 'ZIP':
               $this->generateZIP($generator);
               break;
            default:
               break;
         }
      }
   }

   protected function getPDFGenerator($generator_name, $params = null) {
      if ( file_exists('modules/PDFGenerator/lib/' . $generator_name . '.php') ) {
         require_once 'modules/PDFGenerator/lib/' . $generator_name . '.php';
         return new $generator_name($this->template_id, $this->module_name, $params);
      } else {
         //TODO throw error
         return new PDF($this->template_id, $this->module_name, $params);
      }
   }

   protected function generateZIP($generator) {
      $file_names = array();
      foreach ( $this->root_ids as $bean_id ) {
         $file_names[] = $generator->Output(array( $bean_id ), $this->path . $this->filename_regex, 'F');
      }
      $this->getZipFile($file_names, $generator->getTemplateName());
   }

   protected function getZipFile($files, $zip_name) {
      $zip = new ZipArchive();
      $file_arch = $this->path . $zip_name;
      if ( $zip->open($file_arch, ZIPARCHIVE::OVERWRITE) !== true ) {
         sugar_die(translate('ERR_PROBLEM_WITH_ZIP', 'PDFGenerator'));
      }
      foreach ( $files as $file_name ) {
         $zip->addFile($this->path . $file_name, $file_name);
         // You can not removed immediately because zip file not save properly
      }
      $zip->close();
      foreach ( $files as $file_name ) {
         // we remove only after close
         unlink($this->path . $file_name);
      }
      Header('Location: ' . $this->path . $zip_name); //TODO
   }

   protected function checkParams($generator_name, $params) {
      if ( $generator_name === 'PDFPreView' ) {
         return array();
      }
      $errors = $this->checkTemplate($this->template_id, $this->module_name);
      $errors_module = $this->checkModule($this->module_name);
      if ( empty($errors_module) ) {
         $errors = $this->checkIds($errors, $this->root_ids, $this->module_name);
      }
      return array_merge($errors, $errors_module);
   }

   protected function checkTemplate($template_id, $module_name) {
      $errors = array();
      if ( empty($template_id) ) {
         $errors[] = translate('ERR_NO_TEMPLATE', 'PDFGenerator');
      } else {
         $query = "Select relatedmodule FROM pdftemplates where id='$template_id' and deleted=0";
         $relatedmodule = $this->db->getOne($query);
         if ( $relatedmodule == false ) {
            $errors[] = translate('ERR_WRONG_TEMPLATE_ID', 'PDFGenerator');
         } else if ( $relatedmodule != $module_name ) {
            $errors[] = translate('ERR_MISMATCHED_TEMPLATE', 'PDFGenerator');
         }
      }
      return $errors;
   }

   protected function checkModule($module_name) {
      $errors = array();
      if ( empty($module_name) ) {
         $errors[] = translate('ERR_NO_MODULE_NAME', 'PDFGenerator');
      } else {
         $tmp_bean = BeanFactory::newBean($module_name);
         if ( !($tmp_bean instanceof SugarBean) ) {
            $errors[] = translate('ERR_MODULE_DOES_NOT_EXIST', 'PDFGenerator');
         } else if ( !ACLController::checkAccess($module_name, 'list') ) {
            $errors[] = translate('ERR_NO_ACL', 'PDFGenerator');
         }
      }
      return $errors;
   }

   protected function checkIds($errors, $ids, $module_name) {
      if ( empty($ids) ) {
         $errors[] = translate('ERR_NO_EXISTING_IDS', 'PDFGenerator');
      } else {
         $tmp_bean = BeanFactory::newBean($module_name);
         $ids_str = implode("','", $ids);
         $query = "Select id FROM {$tmp_bean->getTableName()} where deleted=0 and id in('{$ids_str}')";
         $result = $this->db->query($query);
         while ( $row = $this->db->fetchByAssoc($result) ) {
            $new_ids[] = $row['id'];
         }
         $non_exiasting_ids = array_diff($ids, $new_ids);
         if ( empty($new_ids) ) {
            $errors[] = translate('ERR_NO_EXISTING_IDS', 'PDFGenerator');
         }
         if ( !empty($non_exiasting_ids) ) {
            $errors[] = translate('ERR_WRONG_RECORD_IDS', 'PDFGenerator');
            $GLOBALS['log']->fatal(translate('ERR_WRONG_RECORD_IDS_LOG', 'PDFGenerator') . ":" . $module_name . ":" . print_r($non_exiasting_ids, true));
         }
      }
      return $errors;
   }

}
