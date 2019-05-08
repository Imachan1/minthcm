<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

require_once('include/Dashlets/DashletGeneric.php');
require_once('modules/Improvements/Improvements.php');

class ImprovementsDashlet extends DashletGeneric {

   public function __construct($id, $def = null) {
      global $current_user, $app_strings;
      require('modules/Improvements/metadata/dashletviewdefs.php');

      parent::__construct($id, $def);

      if ( empty($def['title']) ) {
         $this->title = translate('LBL_HOMEPAGE_TITLE', 'Improvements');
      }

      $this->searchFields = $dashletData['ImprovementsDashlet']['searchFields'];
      $this->columns = $dashletData['ImprovementsDashlet']['columns'];

      $this->seedBean = BeanFactory::newBean('Improvements');
   }

}
