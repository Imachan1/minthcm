<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

global $current_user;

$dashletData['BenefitsDashlet']['searchFields'] = array(
   'date_entered' => array( 'default' => '' ),
   'date_modified' => array( 'default' => '' ),
   'assigned_user_id' => array(
      'type' => 'assigned_user_name',
      'default' => $current_user->name
   )
);
$dashletData['BenefitsDashlet']['columns'] = array(
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
   'assigned_user_name' => array(
      'width' => '8',
      'label' => 'LBL_LIST_ASSIGNED_USER',
      'default' => true
   ),
);
