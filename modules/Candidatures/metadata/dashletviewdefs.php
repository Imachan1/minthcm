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

global $current_user;

$dashletData['CandidaturesDashlet']['searchFields'] = array(
   'date_entered' => array(
      'default' => ''
   ),
   'date_modified' => array(
      'default' => ''
   ),
   'team_id' => array(
      'default' => ''
   ),
   'assigned_user_id' => array(
      'type' => 'assigned_user_name',
      'default' => $current_user->name
   )
);
$dashletData['CandidaturesDashlet']['columns'] = array(
   'name' => array(
      'label' => 'LBL_LIST_NAME',
      'link' => true,
      'default' => true
   ),
   'date_entered' => array(
      'label' => 'LBL_DATE_ENTERED',
      'default' => true
   ),
   'date_modified' => array(
      'label' => 'LBL_DATE_MODIFIED'
   ),
   'created_by' => array(
      'label' => 'LBL_CREATED'
   ),
   'assigned_user_name' => array(
      'label' => 'LBL_LIST_ASSIGNED_USER'
   ),
);
