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
require_once('modules/Certificates/Certificates.php');

class CertificatesDashlet extends DashletGeneric {

   function CertificatesDashlet($id, $def = null) {
      global $current_user, $app_strings;
      require('modules/Certificates/metadata/dashletviewdefs.php');

      parent::DashletGeneric($id, $def);

      if ( empty($def['title']) )
         $this->title = translate('LBL_HOMEPAGE_TITLE', 'Certificates');

      $this->searchFields = $dashletData['CertificatesDashlet']['searchFields'];
      $this->columns = $dashletData['CertificatesDashlet']['columns'];

      $this->seedBean = new Certificates();
   }

}
