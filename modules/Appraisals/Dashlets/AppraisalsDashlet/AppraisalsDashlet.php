<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

require_once('include/Dashlets/DashletGeneric.php');
require_once('modules/Appraisals/Appraisals.php');

class AppraisalsDashlet extends DashletGeneric {

   public function __construct($id, $def = null) {
      global $current_user, $app_strings;
      require('modules/Appraisals/metadata/dashletviewdefs.php');

      parent::__construct($id, $def);

      if ( empty($def['title']) ) {
         $this->title = translate('LBL_HOMEPAGE_TITLE', 'Appraisals');
      }

      $this->searchFields = $dashletData['AppraisalsDashlet']['searchFields'];
      $this->columns = $dashletData['AppraisalsDashlet']['columns'];

      $this->seedBean = BeanFactory::newBean('Appraisals');
   }

}
