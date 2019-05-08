<?php

$popupMeta = array(
   'moduleMain' => 'CompetencyRatings',
   'varName' => 'CompetencyRatings',
   'orderBy' => 'competencyratings.name',
   'whereClauses' => array(
      'name' => 'competencyratings.name',
      'rating' => 'competencyratings.rating',
      'competency_name' => 'competencyratings.competency_name',
      'assigned_user_name' => 'competencyratings.assigned_user_name',
   ),
   'searchInputs' => array(
      'name',
      'rating',
      'assigned_user_name',
   ),
   'searchdefs' => array(
      'name' =>
      array(
         'name' => 'name',
         'width' => '10%',
      ),
      'rating' =>
      array(
         'type' => 'enum',
         'studio' => 'visible',
         'label' => 'LBL_RATING',
         'width' => '10%',
         'name' => 'rating',
      ),
      'competency_name' =>
      array(
         'type' => 'relate',
         'link' => true,
         'label' => 'LBL_COMPETENCY_NAME',
         'id' => 'COMPETENCY_ID',
         'width' => '10%',
         'name' => 'competency_name',
      ),
      'assigned_user_name' =>
      array(
         'link' => true,
         'type' => 'relate',
         'label' => 'LBL_ASSIGNED_TO_NAME',
         'id' => 'ASSIGNED_USER_ID',
         'width' => '10%',
         'name' => 'assigned_user_name',
      ),
   ),
);
