<?php

$module_name = 'CompetencyRatings';
$listViewDefs [$module_name] = array(
   'NAME' =>
   array(
      'width' => '32%',
      'label' => 'LBL_NAME',
      'default' => true,
      'link' => true,
   ),
   'RATING' =>
   array(
      'type' => 'enum',
      'studio' => 'visible',
      'label' => 'LBL_RATING',
      'width' => '10%',
      'default' => true,
   ),
   'COMPETENCY_NAME' =>
   array(
      'type' => 'relate',
      'link' => true,
      'label' => 'LBL_COMPETENCY_NAME',
      'id' => 'COMPETENCY_ID',
      'width' => '10%',
      'default' => true,
   ),
   'PARENT_NAME' => array(
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
   'ASSIGNED_USER_NAME' =>
   array(
      'width' => '9%',
      'label' => 'LBL_ASSIGNED_TO_NAME',
      'module' => 'Employees',
      'id' => 'ASSIGNED_USER_ID',
      'default' => true,
   ),
   'DATE_ENTERED' =>
   array(
      'type' => 'datetime',
      'label' => 'LBL_DATE_ENTERED',
      'width' => '10%',
      'default' => false,
   ),
   'DATE_MODIFIED' =>
   array(
      'type' => 'datetime',
      'label' => 'LBL_DATE_MODIFIED',
      'width' => '10%',
      'default' => false,
   ),
   'MODIFIED_BY_NAME' =>
   array(
      'type' => 'relate',
      'link' => true,
      'label' => 'LBL_MODIFIED_NAME',
      'id' => 'MODIFIED_USER_ID',
      'width' => '10%',
      'default' => false,
   ),
   'CREATED_BY_NAME' =>
   array(
      'type' => 'relate',
      'link' => true,
      'label' => 'LBL_CREATED',
      'id' => 'CREATED_BY',
      'width' => '10%',
      'default' => false,
   ),
);
;
?>
