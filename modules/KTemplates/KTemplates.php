<?php

require_once('modules/KTemplates/hooks_controller.php');

class KTemplates extends Basic {

   public $new_schema = true;
   public $module_dir = 'KTemplates';
   public $object_name = 'KTemplates';
   public $table_name = 'ktemplates';
   public $importable = false;
   public $disable_row_level_security = true; // to ensure that modules created and deployed under CE will continue to function under team security if the instance is upgraded to PRO
   public $id;
   public $name;
   public $date_entered;
   public $date_modified;
   public $modified_user_id;
   public $modified_by_name;
   public $created_by;
   public $created_by_name;
   public $description;
   public $deleted;
   public $created_by_link;
   public $modified_user_link;
   public $assigned_user_id;
   public $assigned_user_name;
   public $assigned_user_link;
   public $template;
   public $relatedmodule;
   public $is_default = 0;

   public function __construct() {
      parent::__construct();
   }

   public function bean_implements($interface) {
      if ( "ACL" === $interface ) {
         return true;
      } else {
         return false;
      }
   }

   public function save($check_notify = FALSE) {
      // need to call hook before Sute 7.10 cleanBean function - it cleans <!--repeat--> tags
      if ( empty($this->id) ) {
         $this->id = create_guid();
         $this->new_with_id = true;
      }
      $this->saveTemplate();
      $return_id = parent::save($check_notify);

      //save template to config file
      $path = 'modules/KReports/Plugins/Integration/kpdfexport/templates/config.php';
      include $path;
      if ( !isset($relation_config[$this->relatedmodule]) ) {
         $relation_config[$this->relatedmodule] = array();
      }
      if ( array_search($this->id, $relation_config[$this->relatedmodule]) === false ) {
         $is_default = array_search("Default", $relation_config[$this->relatedmodule]);
         if ( $is_default === 0 )
            $is_default = true;
         if ( $is_default == false ) {
            $relation_config[$this->relatedmodule][] = 'Default';
         }
         $relation_config[$this->relatedmodule][] = $this->id;
         $relation_config[$this->relatedmodule] = $this->my_sort_array($relation_config[$this->relatedmodule]);
         write_array_to_file('relation_config', $relation_config, $path);
      }
      SugarApplication::redirect("index.php?module=KTemplates&action=DetailView&record={$this->id}");
      return $return_id;
   }

   public function mark_deleted($id) {
      $path = 'modules/KReports/Plugins/Integration/kpdfexport/templates/config.php';
      include $path;
      $key = array_search($this->id, $relation_config[$this->relatedmodule]);
      unset($relation_config[$this->relatedmodule][$key]);
      $fp = sugar_fopen($path, 'w');
      fclose($fp);
      $relation_config[$this->relatedmodule] = $this->my_sort_array($relation_config[$this->relatedmodule]);
      write_array_to_file('relation_config', $relation_config, $path);
      parent::mark_deleted($id);
   }

   function my_sort_array($array) {
      $return_array = array();
      foreach ( $array as $value ) {
         $return_array[] = $value;
      }
      return $return_array;
   }

   protected function saveTemplate() {
      if ( $this->is_default ) {
         $query = "UPDATE `ktemplates` SET `is_default` = '0' WHERE `relatedmodule`='" . $bean->relatedmodule . "' AND `is_default`=1 AND `deleted`=0";
         $result = $this->db->query($query);
      }
      $this->template = SugarCleaner::cleanHtml($this->fixSpecialLetters($this->template));
      //save template as file
      $this->saveTemplateAsFile("modules/KReports/Plugins/Integration/kpdfexport/templates/" . $this->id . ".html", $this->template);
   }

   protected function saveTemplateAsFile($file_path, $template) {
      $handle = fopen($file_path, "w+");
      $tmp = html_entity_decode($template);
      fwrite($handle, $tmp);
      fclose($handle);
   }

   protected function fixSpecialLetters($template) {
      return str_replace(array( '&Oacute;', '&oacute;', '&nbsp;', '§' ), array( 'Ó', 'ó', ' ', '&sect;' ), $template);
   }

}
