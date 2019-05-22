<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}
/*
 * Your installation or use of this SugarCRM file is subject to the applicable
 * terms available at
 * http://support.sugarcrm.com/06_Customer_Center/10_Master_Subscription_Agreements/.
 * If you do not agree to all of the applicable terms or do not have the
 * authority to bind the entity as an authorized representative, then do not
 * install or use this SugarCRM file.
 *
 * Copyright (C) SugarCRM Inc. All rights reserved.
 */
require_once('include/Dashlets/DashletGeneric.php');
require_once('modules/Applications/Applications.php');

class ApplicationsDashlet extends DashletGeneric {

   function ApplicationsDashlet($id, $def = null) {
      require('modules/Applications/metadata/dashletviewdefs.php');

      parent::DashletGeneric($id, $def);

      if ( empty($def['title']) )
         $this->title = translate('LBL_HOMEPAGE_TITLE', 'Applications');

      $this->searchFields = $dashletData['ApplicationsDashlet']['searchFields'];
      $this->columns = $dashletData['ApplicationsDashlet']['columns'];

      $this->seedBean = new Applications();
   }

}
