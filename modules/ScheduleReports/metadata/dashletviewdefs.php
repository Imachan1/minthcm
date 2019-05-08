<?php

if ( !defined('sugarEntry') || !sugarEntry )
   die('Not A Valid Entry Point');

$dashletData['ScheduleReportsDashlet']['searchFields'] = array(
   'date_entered' => array(
      'default' => ''
   ),
   'date_modified' => array(
      'default' => ''
   ),
);

$dashletData['ScheduleReportsDashlet']['columns'] = array(
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
      'label' => 'LBL_DATE_MODIFIED'
   ),
   'created_by' => array(
      'width' => '8',
      'label' => 'LBL_CREATED'
   ),
);
