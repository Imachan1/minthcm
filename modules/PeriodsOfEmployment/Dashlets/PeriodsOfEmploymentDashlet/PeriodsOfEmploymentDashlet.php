<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

require_once('include/Dashlets/DashletGeneric.php');
require_once('modules/PeriodsOfEmployment/PeriodsOfEmployment.php');

class PeriodsOfEmploymentDashlet extends DashletGeneric {

   public function __construct($id, $def = null) {
      require('modules/PeriodsOfEmployment/metadata/dashletviewdefs.php');

      parent::__construct($id, $def);

      if ( empty($def['title']) ) {
         $this->title = translate('LBL_HOMEPAGE_TITLE', 'PeriodsOfEmployment');
      }

      $this->searchFields = $dashletData['PeriodsOfEmploymentDashlet']['searchFields'];
      $this->columns = $dashletData['PeriodsOfEmploymentDashlet']['columns'];

      $this->seedBean = BeanFactory::newBean('PeriodsOfEmployment');
   }

}
