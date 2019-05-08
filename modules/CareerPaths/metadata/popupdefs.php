<?php

$popupMeta = array(
   'moduleMain' => 'CareerPaths',
   'varName' => 'CareerPaths',
   'orderBy' => 'careerpaths.name',
   'whereClauses' => array(
      'name' => 'careerpaths.name',
   ),
   'searchInputs' => array(
      1 => 'name',
   ),
   'searchdefs' => array(
      'name' => array(
         'name' => 'name',
         'width' => '10%',
      ),
      'position_from_name' => array(
         'type' => 'relate',
         'link' => true,
         'label' => 'LBL_POSITION_FROM_NAME',
         'id' => 'POSITION_FROM_ID',
         'width' => '10%',
         'name' => 'position_from_name',
      ),
      'position_to_name' => array(
         'type' => 'relate',
         'link' => true,
         'label' => 'LBL_POSITION_TO_NAME',
         'id' => 'POSITION_TO_ID',
         'width' => '10%',
         'name' => 'position_to_name',
      ),
   ),
);
