<?php

if ( !defined('sugarEntry') || !sugarEntry ){
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

$module_name = 'Positions';
$listViewDefs[$module_name] = array(
   'NAME' => array(
      'name' => 'name',
      'label' => 'LBL_NAME',
      'default' => true,
      'enabled' => true,
      'link' => true,
   ),
   'STATUS' =>
   array(
      'label' => 'LBL_STATUS',
      'default' => true,
   ),
   'ORGANIZATIONALUNITS_LEADER_NAME' => array(
      'name' => 'organizationalunits_leader_name',
      'label' => 'LBL_ORGANIZATIONALUNITS_LEADER_NAME',
      'id' => 'ORGANIZATIONALUNITS_LEADER_ID',
      'enabled' => true,
      'default' => true,
   ),
   'POSITIONS_SUPERVISION_NAME' => array(
      'name' => 'positions_supervision_name',
      'label' => 'LBL_POSITIONS_SUPERVISION_NAME',
      'enabled' => true,
      'default' => true,
   ),
   'ASSIGNED_USER_NAME' => array(
      'name' => 'assigned_user_name',
      'label' => 'LBL_ASSIGNED_TO_NAME',
      'default' => true,
      'enabled' => true,
      'link' => true,
   ),
   'DATE_MODIFIED' => array(
      'label' => 'LBL_DATE_MODIFIED',
      'enabled' => true,
      'default' => true,
      'name' => 'date_modified',
      'readonly' => true,
   ),
);
