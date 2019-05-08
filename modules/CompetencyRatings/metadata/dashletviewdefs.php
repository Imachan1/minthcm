<?php

$dashletData['CompetencyRatingsDashlet']['searchFields'] = array(
   'name' =>
   array(
      'default' => '',
   ),
   'rating' =>
   array(
      'default' => '',
   ),
   'competency_name' =>
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
   'assigned_user_name' =>
   array(
      'default' => '',
   ),
);
$dashletData['CompetencyRatingsDashlet']['columns'] = array(
   'name' =>
   array(
      'width' => '40%',
      'label' => 'LBL_LIST_NAME',
      'link' => true,
      'default' => true,
      'name' => 'name',
   ),
   'rating' =>
   array(
      'type' => 'enum',
      'studio' => 'visible',
      'label' => 'LBL_RATING',
      'width' => '10%',
      'default' => true,
      'name' => 'rating',
   ),
   'competency_name' =>
   array(
      'width' => '8%',
      'label' => 'LBL_COMPETENCY_NAME',
      'name' => 'competency_name',
      'default' => true,
   ),
   'parent_name' => array(
      'width' => '20',
      'label' => 'LBL_PARENT_NAME',
      'default' => true,
      'link' => true,
      'id' => 'PARENT_ID',
      'dynamic_module' => 'PARENT_TYPE',
      'related_fields' =>
      array(
         0 => 'parent_id',
         1 => 'parent_type',
      ),
   ),
   'assigned_user_name' =>
   array(
      'width' => '8%',
      'label' => 'LBL_LIST_ASSIGNED_USER',
      'name' => 'assigned_user_name',
      'default' => true,
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
   'created_by_name' =>
   array(
      'type' => 'relate',
      'link' => true,
      'label' => 'LBL_CREATED',
      'id' => 'CREATED_BY',
      'width' => '10%',
      'default' => false,
   ),
   'modified_by_name' =>
   array(
      'type' => 'relate',
      'link' => true,
      'label' => 'LBL_MODIFIED_NAME',
      'id' => 'MODIFIED_USER_ID',
      'width' => '10%',
      'default' => false,
   ),
);
