<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

require_once('include/Dashlets/DashletGeneric.php');
require_once('modules/Conclusions/Conclusions.php');

class ConclusionsDashlet extends DashletGeneric {

   public function __construct($id, $def = null) {
      require('modules/Conclusions/metadata/dashletviewdefs.php');

      parent::__construct($id, $def);

      if ( empty($def['title']) ) {
         $this->title = translate('LBL_HOMEPAGE_TITLE', 'Conclusions');
      }

      $this->searchFields = $dashletData['ConclusionsDashlet']['searchFields'];
      $this->columns = $dashletData['ConclusionsDashlet']['columns'];

      $this->seedBean = BeanFactory::newBean('Conclusions');
   }

}
