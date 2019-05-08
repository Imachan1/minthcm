<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

global $current_user;

$dashletData['ConclusionsDashlet']['searchFields'] = array(
   'date_entered' => array( 'default' => '' ),
   'date_modified' => array( 'default' => '' ),
   'created_by_name' => array( 'default' => '' ),
   'modified_by_name' => array( 'default' => '' ),
   'assigned_user_id' => array(
      'type' => 'assigned_user_name',
      'default' => $current_user->name
   ),
   'meeting_name' => array(
      'default' => '',
   ),
);
$dashletData['ConclusionsDashlet']['columns'] = array(
   'name' => array(
      'width' => '40',
      'label' => 'LBL_LIST_NAME',
      'link' => true,
      'default' => true
   ),
   'date_entered' => array(
      'width' => '15',
      'label' => 'LBL_DATE_ENTERED',
      'default' => true
   ),
   'date_modified' => array(
      'width' => '15',
      'label' => 'LBL_DATE_MODIFIED',
      'default' => true
   ),
   'created_by_name' =>
   array(
      'type' => 'relate',
      'link' => true,
      'label' => 'LBL_CREATED',
      'id' => 'CREATED_BY',
      'width' => '10%',
      'default' => false,
      'name' => 'created_by_name',
   ),
   'modified_by_name' =>
   array(
      'type' => 'relate',
      'link' => true,
      'label' => 'LBL_MODIFIED_NAME',
      'id' => 'MODIFIED_USER_ID',
      'width' => '10%',
      'default' => false,
      'name' => 'modified_by_name',
   ),
   'assigned_user_name' => array(
      'width' => '8',
      'label' => 'LBL_LIST_ASSIGNED_USER',
      'default' => true
   ),
   'meeting_name' => array(
      'name' => 'meeting_name',
      'type' => 'relate',
      'link' => true,
      'label' => 'LBL_MEETING_NAME',
      'id' => 'MEETING_ID',
      'width' => '10%',
      'default' => true,
   ),
);
