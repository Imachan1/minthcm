<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

require_once('include/Dashlets/DashletGeneric.php');
require_once('modules/Problems/Problems.php');

class ProblemsDashlet extends DashletGeneric {

   public function __construct($id, $def = null) {
      require('modules/Problems/metadata/dashletviewdefs.php');

      parent::__construct($id, $def);

      if ( empty($def['title']) ) {
         $this->title = translate('LBL_HOMEPAGE_TITLE', 'Problems');
      }

      $this->searchFields = $dashletData['ProblemsDashlet']['searchFields'];
      $this->columns = $dashletData['ProblemsDashlet']['columns'];

      $this->seedBean = BeanFactory::newBean('Problems');
   }

}
