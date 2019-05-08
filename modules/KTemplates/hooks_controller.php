<?php

class template_hooks {

   function after_save($bean, $event, $arguments) {
      $language_path = "modules/KReports/additional_language.php";
      include $language_path;
      $fpl = sugar_fopen($language_path, 'w');
      fclose($fpl);
      $db = DBManagerFactory::getInstance();
      $ktemplate_query = $db->query("SELECT id,name FROM ktemplates WHERE deleted=0");
      while ( $ktemplate = $db->fetchByAssoc($ktemplate_query) ) {
         $additional_language[$ktemplate['id']] = $ktemplate['name'];
      }
      write_array_to_file('mod_strings', $additional_language, $language_path);
   }

   function after_retrieve($bean, $event, $arguments) {
      //load template file
      $filename = "modules/KReports/Plugins/Integration/kpdfexport/templates/" . $bean->id . ".html";
      $content = file_get_contents($filename);
      $bean->template = $content;
   }

}
