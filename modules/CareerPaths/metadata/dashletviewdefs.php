<?php

$dashletData['CareerPathsDashlet']['searchFields'] = array(
   'name' =>
   array(
      'default' => '',
   ),
   'date_entered' =>
   array(
      'default' => '',
   ),
   'date_modified' =>
   array(
      'default' => '',
   ),
);
$dashletData['CareerPathsDashlet']['columns'] = array(
   'name' =>
   array(
      'width' => '40%',
      'label' => 'LBL_LIST_NAME',
      'link' => true,
      'default' => true,
      'name' => 'name',
   ),
   'position_from_name' =>
   array(
      'width' => '15%',
      'label' => 'LBL_POSITION_FROM_NAME',
      'name' => 'position_from_name',
      'default' => false,
   ),
   'position_to_name' =>
   array(
      'width' => '15%',
      'label' => 'LBL_POSITION_TO_NAME',
      'name' => 'position_to_name',
      'default' => false,
   ),
   'date_modified' =>
   array(
      'width' => '15%',
      'label' => 'LBL_DATE_MODIFIED',
      'name' => 'date_modified',
      'default' => false,
   ),
   'date_entered' =>
   array(
      'width' => '15%',
      'label' => 'LBL_DATE_ENTERED',
      'default' => false,
      'name' => 'date_entered',
   ),
);
