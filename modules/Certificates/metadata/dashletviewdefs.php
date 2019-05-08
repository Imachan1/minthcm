<?php

$dashletData['CertificatesDashlet']['searchFields'] = array(
   'name' =>
   array(
      'default' => '',
   ),
   'start_date' =>
   array(
      'default' => '',
   ),
   'end_date' =>
   array(
      'default' => '',
   ),
   'status' =>
   array(
      'default' => '',
   ),
   'employee_name' => array( 'default' => '' ),
   'candidate_name' => array(
      'default' => '',
   ),
);
$dashletData['CertificatesDashlet']['columns'] = array(
   'name' =>
   array(
      'width' => '40%',
      'label' => 'LBL_NAME', //LBL_LIST_NAME
      'link' => true,
      'default' => true,
      'name' => 'name',
   ),
   'start_date' =>
   array(
      'width' => '15%',
      'label' => 'LBL_START_DATE',
      'default' => true,
      'name' => 'start_date',
   ),
   'end_date' =>
   array(
      'width' => '15%',
      'label' => 'LBL_END_DATE',
      'default' => true,
      'name' => 'end_date',
   ),
   'status' =>
   array(
      'type' => 'enum',
      'width' => '15%',
      'label' => 'LBL_STATUS',
      'name' => 'status',
      'default' => false,
   ),
   'employee_name' => array(
      'width' => '15',
      'label' => 'LBL_EMPLOYEE',
      'default' => false
   ),
   'candidate_name' => array(
      'name' => 'candidate_name',
      'type' => 'relate',
      'link' => true,
      'label' => 'LBL_RELATIONSHIP_CANDIDATE_NAME',
      'id' => 'CANDIDATE_ID',
      'width' => '10%',
      'default' => true,
   ),
);
