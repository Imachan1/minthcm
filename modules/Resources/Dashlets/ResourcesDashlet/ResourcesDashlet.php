<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

require_once('include/Dashlets/DashletGeneric.php');
require_once('modules/Resources/Resources.php');

class ResourcesDashlet extends DashletGeneric {

   public function __construct($id, $def = null) {
      require('modules/Resources/metadata/dashletviewdefs.php');

      parent::__construct($id, $def);

      if ( empty($def['title']) ) {
         $this->title = translate('LBL_HOMEPAGE_TITLE', 'Resources');
      }

      $this->searchFields = $dashletData['ResourcesDashlet']['searchFields'];
      $this->columns = $dashletData['ResourcesDashlet']['columns'];

      $this->seedBean = new Resources();
   }

}
