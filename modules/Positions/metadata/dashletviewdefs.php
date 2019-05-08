<?php

if ( !defined('sugarEntry') || !sugarEntry ){
   die('Not A Valid Entry Point');
}
/*
 * Your installation or use of this SugarCRM file is subject to the applicable
 * terms available at
 * http:
 * If you do not agree to all of the applicable terms or do not have the
 * authority to bind the entity as an authorized representative, then do not
 * install or use this SugarCRM file.
 *
 * Copyright (C) SugarCRM Inc. All rights reserved.
 */



global $current_user;

$dashletData['PositionsDashlet']['searchFields'] = array(
   'date_entered' => array( 'default' => '' ),
   'date_modified' => array( 'default' => '' ),
   'assigned_user_id' => array(
      'type' => 'assigned_user_name',
      'default' => $current_user->name
   ),
   'status' => array( 'type' => 'enum', 'default' => '' ),
   'positions_supervision_name' => array( 'default' => '' ),
);
$dashletData['PositionsDashlet']['columns'] = array( 'name' => array(
      'label' => 'LBL_LIST_NAME',
      'link' => true,
      'default' => true
   ),
   'status' => array(
      'label' => 'LBL_STATUS',
      'width' => '15%',
      'default' => true,
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
   'positions_supervision_name' => array(
      'name' => 'positions_supervision_name',
      'type' => 'relate',
      'link' => true,
      'label' => 'LBL_POSITIONS_SUPERVISION_NAME',
      'id' => 'POSITIONS_SUPERVISION_ID',
      'width' => '10%',
      'default' => false,
   ),
   'organizationalunits_leader_name' => array(
      'name' => 'organizationalunits_leader_name',
      'type' => 'relate',
      'link' => true,
      'label' => 'LBL_ORGANIZATIONALUNITS_LEADER_NAME',
      'id' => 'ORGANIZATIONALUNITS_LEADER_NAME',
      'width' => '10%',
      'default' => false,
   ),
);
