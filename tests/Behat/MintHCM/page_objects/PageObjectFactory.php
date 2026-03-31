<?php

namespace eVolpe\MintHCM;

class PageObjectFactory extends \eVolpe\Suite\PageObjectFactory
{

    protected function generatePageFullName($page_name)
    {
        $full_class_name = 'eVolpe\\Suite\\' . $page_name;
      if ( !class_exists($full_class_name) ) {
         throw new \Exception("Class {$full_class_name} does not exists!!");         
      }
      return $full_class_name;
    }

    protected function generateModulePageFullName($module, $page_type, $with_id = false)
    {
        $page_type = $this->fixPageType($page_type, $with_id);

      if ( !in_array($page_type, array( 'list', 'detail', 'edit' )) ) {
         throw new \Exception("Improper view name " . $page_type . " for module " . $module);
      }

      $page_type = ucfirst(strtolower($page_type));
      $module_page_class_name = 'eVolpe\\Suite\\' . $module . $page_type . 'Page';
      if ( class_exists($module_page_class_name) ) {
         return $module_page_class_name;
      }

      $full_class_name = 'CRMModule' . $page_type . 'Page';

      return $full_class_name;
    }

}
